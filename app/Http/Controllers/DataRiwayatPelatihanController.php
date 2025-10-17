<?php

namespace App\Http\Controllers;

use App\Models\DataRiwayatPelatihan;
use App\Models\DataPegawai;
use App\Models\TabelJenisPelatihan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

/**
 * DataRiwayatPelatihanController
 * 
 * Controller untuk mengelola data riwayat pelatihan pegawai.
 * Menyediakan CRUD operations untuk model DataRiwayatPelatihan.
 * 
 * @package App\Http\Controllers
 * @author Laravel Filament SIMKA9
 * @version 1.0.0
 */
class DataRiwayatPelatihanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            $search = $request->get('search');
            $nikPegawai = $request->get('nik_pegawai');
            
            $query = DataRiwayatPelatihan::with(['pegawai', 'jenisPelatihan']);

            // Filter by specific employee if provided
            if ($nikPegawai) {
                $query->where('nik_data_pegawai', $nikPegawai);
            }

            // Filter by nik_data_pegawai parameter (for compatibility)
            if ($request->get('nik_data_pegawai')) {
                $query->where('nik_data_pegawai', $request->get('nik_data_pegawai'));
            }

            // Filter by jenis pelatihan
            if ($request->get('kode_tabel_jenis_pelatihan')) {
                $query->where('kode_tabel_jenis_pelatihan', $request->get('kode_tabel_jenis_pelatihan'));
            }

            // Filter by date range
            if ($request->get('tanggal_dari')) {
                $query->where('tanggal', '>=', $request->get('tanggal_dari'));
            }
            if ($request->get('tanggal_sampai')) {
                $query->where('tanggal', '<=', $request->get('tanggal_sampai'));
            }

            // Search functionality
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('penyelenggara', 'like', "%{$search}%")
                      ->orWhere('nomor', 'like', "%{$search}%")
                      ->orWhere('angkatan', 'like', "%{$search}%")
                      ->orWhereHas('pegawai', function ($subQ) use ($search) {
                          $subQ->where('nama_lengkap', 'like', "%{$search}%")
                               ->orWhere('nip', 'like', "%{$search}%");
                      })
                      ->orWhereHas('jenisPelatihan', function ($subQ) use ($search) {
                          $subQ->where('nama', 'like', "%{$search}%");
                      });
                });
            }

            $riwayatPelatihan = $query->orderBy('urut', 'asc')
                                     ->orderBy('tanggal', 'desc')
                                     ->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Data riwayat pelatihan berhasil diambil',
                'data' => $riwayatPelatihan
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data riwayat pelatihan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return JsonResponse
     */
    public function create(): JsonResponse
    {
        try {
            $dataPegawai = DataPegawai::select('nik', 'nip', 'nama_lengkap')
                                    ->orderBy('nama_lengkap')
                                    ->get();
            
            $jenisPelatihan = TabelJenisPelatihan::select('kode', 'nama')
                                               ->orderBy('nama')
                                               ->get();

            return response()->json([
                'success' => true,
                'message' => 'Form create riwayat pelatihan',
                'data' => [
                    'data_pegawai' => $dataPegawai,
                    'jenis_pelatihan' => $jenisPelatihan,
                    'form_fields' => $this->getFormFields()
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data form',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'nik_data_pegawai' => 'required|string|max:255|exists:data_pegawai,nik',
                'nama' => 'required|string|max:255',
                'kode_tabel_jenis_pelatihan' => 'required|string|max:10|exists:tabel_jenis_pelatihan,kode',
                'penyelenggara' => 'required|string|max:255',
                'angkatan' => 'nullable|string|max:50',
                'nomor' => 'nullable|string|max:100',
                'tanggal' => 'required|date',
                'tanggal_sertifikat' => 'nullable|date|after_or_equal:tanggal',
                'berkas' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'urut' => 'required|integer|min:1|max:999',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();

            // Handle file upload
            if ($request->hasFile('berkas')) {
                $file = $request->file('berkas');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('pelatihan-certificates', $filename, 'public');
                $data['berkas'] = $path;
            }

            // Check for duplicate entry
            $exists = DataRiwayatPelatihan::where('nik_data_pegawai', $data['nik_data_pegawai'])
                                        ->where('nama', $data['nama'])
                                        ->where('penyelenggara', $data['penyelenggara'])
                                        ->where('tanggal', $data['tanggal'])
                                        ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data riwayat pelatihan dengan kombinasi yang sama sudah ada',
                ], 422);
            }

            $riwayatPelatihan = DataRiwayatPelatihan::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Data riwayat pelatihan berhasil dibuat',
                'data' => $riwayatPelatihan->load(['pegawai', 'jenisPelatihan'])
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat data riwayat pelatihan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $riwayatPelatihan = DataRiwayatPelatihan::with(['pegawai', 'jenisPelatihan'])
                                                  ->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Data riwayat pelatihan berhasil diambil',
                'data' => $riwayatPelatihan
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data riwayat pelatihan tidak ditemukan'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data riwayat pelatihan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function edit(int $id): JsonResponse
    {
        try {
            $riwayatPelatihan = DataRiwayatPelatihan::with(['pegawai', 'jenisPelatihan'])
                                                  ->findOrFail($id);
            
            $dataPegawai = DataPegawai::select('nik', 'nip', 'nama_lengkap')
                                    ->orderBy('nama_lengkap')
                                    ->get();
            
            $jenisPelatihan = TabelJenisPelatihan::select('kode', 'nama')
                                               ->orderBy('nama')
                                               ->get();

            return response()->json([
                'success' => true,
                'message' => 'Form edit riwayat pelatihan',
                'data' => [
                    'riwayat_pelatihan' => $riwayatPelatihan,
                    'data_pegawai' => $dataPegawai,
                    'jenis_pelatihan' => $jenisPelatihan,
                    'form_fields' => $this->getFormFields()
                ]
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data riwayat pelatihan tidak ditemukan'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data form edit',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $riwayatPelatihan = DataRiwayatPelatihan::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'nik_data_pegawai' => 'required|string|max:255|exists:data_pegawai,nik',
                'nama' => 'required|string|max:255',
                'kode_tabel_jenis_pelatihan' => 'required|string|max:10|exists:tabel_jenis_pelatihan,kode',
                'penyelenggara' => 'required|string|max:255',
                'angkatan' => 'nullable|string|max:50',
                'nomor' => 'nullable|string|max:100',
                'tanggal' => 'required|date',
                'tanggal_sertifikat' => 'nullable|date|after_or_equal:tanggal',
                'berkas' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'urut' => 'required|integer|min:1|max:999',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();

            // Handle file upload
            if ($request->hasFile('berkas')) {
                // Delete old file if exists
                if ($riwayatPelatihan->berkas && Storage::disk('public')->exists($riwayatPelatihan->berkas)) {
                    Storage::disk('public')->delete($riwayatPelatihan->berkas);
                }

                $file = $request->file('berkas');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('pelatihan-certificates', $filename, 'public');
                $data['berkas'] = $path;
            }

            // Check for duplicate entry (excluding current record)
            $exists = DataRiwayatPelatihan::where('nik_data_pegawai', $data['nik_data_pegawai'])
                                        ->where('nama', $data['nama'])
                                        ->where('penyelenggara', $data['penyelenggara'])
                                        ->where('tanggal', $data['tanggal'])
                                        ->where('id', '!=', $id)
                                        ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data riwayat pelatihan dengan kombinasi yang sama sudah ada',
                ], 422);
            }

            $riwayatPelatihan->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Data riwayat pelatihan berhasil diperbarui',
                'data' => $riwayatPelatihan->load(['pegawai', 'jenisPelatihan'])
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data riwayat pelatihan tidak ditemukan'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data riwayat pelatihan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $riwayatPelatihan = DataRiwayatPelatihan::findOrFail($id);
            
            // Delete associated file if exists
            if ($riwayatPelatihan->berkas && Storage::disk('public')->exists($riwayatPelatihan->berkas)) {
                Storage::disk('public')->delete($riwayatPelatihan->berkas);
            }
            
            $riwayatPelatihan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data riwayat pelatihan berhasil dihapus'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data riwayat pelatihan tidak ditemukan'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data riwayat pelatihan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download certificate file
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|JsonResponse
     */
    public function downloadCertificate(int $id)
    {
        try {
            $riwayatPelatihan = DataRiwayatPelatihan::findOrFail($id);
            
            if (!$riwayatPelatihan->berkas || !Storage::disk('public')->exists($riwayatPelatihan->berkas)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File sertifikat tidak ditemukan'
                ], 404);
            }

            $filename = 'sertifikat-' . $riwayatPelatihan->nama . '.' . pathinfo($riwayatPelatihan->berkas, PATHINFO_EXTENSION);
            
            return response()->download(
                Storage::disk('public')->path($riwayatPelatihan->berkas),
                $filename
            );
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data riwayat pelatihan tidak ditemukan'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengunduh sertifikat',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get riwayat pelatihan by employee NIK
     *
     * @param string $nik
     * @return JsonResponse
     */
    public function getByEmployee(string $nik): JsonResponse
    {
        try {
            $riwayatPelatihan = DataRiwayatPelatihan::with(['jenisPelatihan'])
                                                  ->where('nik_data_pegawai', $nik)
                                                  ->orderBy('urut', 'asc')
                                                  ->orderBy('tanggal', 'desc')
                                                  ->get();

            return response()->json([
                'success' => true,
                'message' => 'Riwayat pelatihan pegawai berhasil diambil',
                'data' => $riwayatPelatihan
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data riwayat pelatihan pegawai',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get statistics for pelatihan
     *
     * @return JsonResponse
     */
    public function getStatistics(): JsonResponse
    {
        try {
            $totalPelatihan = DataRiwayatPelatihan::count();
            $pelatihanWithCertificate = DataRiwayatPelatihan::whereNotNull('berkas')->count();
            $pelatihanThisYear = DataRiwayatPelatihan::whereYear('tanggal', date('Y'))->count();
            $uniqueEmployees = DataRiwayatPelatihan::distinct('nik_data_pegawai')->count();

            return response()->json([
                'success' => true,
                'message' => 'Statistik pelatihan berhasil diambil',
                'data' => [
                    'total_pelatihan' => $totalPelatihan,
                    'pelatihan_with_certificate' => $pelatihanWithCertificate,
                    'pelatihan_this_year' => $pelatihanThisYear,
                    'unique_employees' => $uniqueEmployees,
                    'certificate_percentage' => $totalPelatihan > 0 ? round(($pelatihanWithCertificate / $totalPelatihan) * 100, 2) : 0
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik pelatihan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get form fields definition
     *
     * @return array
     */
    private function getFormFields(): array
    {
        return [
            'nik_data_pegawai' => [
                'type' => 'select',
                'label' => 'Pegawai',
                'required' => true,
                'validation' => 'required|string|max:255|exists:data_pegawai,nik'
            ],
            'nama' => [
                'type' => 'text',
                'label' => 'Nama Pelatihan',
                'required' => true,
                'validation' => 'required|string|max:255'
            ],
            'kode_tabel_jenis_pelatihan' => [
                'type' => 'select',
                'label' => 'Jenis Pelatihan',
                'required' => true,
                'validation' => 'required|string|max:10|exists:tabel_jenis_pelatihan,kode'
            ],
            'penyelenggara' => [
                'type' => 'text',
                'label' => 'Penyelenggara',
                'required' => true,
                'validation' => 'required|string|max:255'
            ],
            'angkatan' => [
                'type' => 'text',
                'label' => 'Angkatan',
                'required' => false,
                'validation' => 'nullable|string|max:50'
            ],
            'nomor' => [
                'type' => 'text',
                'label' => 'Nomor Sertifikat',
                'required' => false,
                'validation' => 'nullable|string|max:100'
            ],
            'tanggal' => [
                'type' => 'date',
                'label' => 'Tanggal Pelatihan',
                'required' => true,
                'validation' => 'required|date'
            ],
            'tanggal_sertifikat' => [
                'type' => 'date',
                'label' => 'Tanggal Sertifikat',
                'required' => false,
                'validation' => 'nullable|date|after_or_equal:tanggal'
            ],
            'berkas' => [
                'type' => 'file',
                'label' => 'Berkas Sertifikat',
                'required' => false,
                'validation' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120'
            ],
            'urut' => [
                'type' => 'number',
                'label' => 'Urutan',
                'required' => true,
                'validation' => 'required|integer|min:1|max:999'
            ]
        ];
    }
}