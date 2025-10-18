<?php

namespace App\Http\Controllers;

use App\Models\DataRiwayatUnitKerjaLain;
use App\Models\DataPegawai;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

/**
 * DataRiwayatUnitKerjaLainController
 * 
 * Controller untuk mengelola data riwayat unit kerja lain pegawai.
 * Menyediakan CRUD operations untuk model DataRiwayatUnitKerjaLain.
 * 
 * @package App\Http\Controllers
 * @author Laravel Filament SIMKA9
 * @version 1.0.0
 */
class DataRiwayatUnitKerjaLainController extends Controller
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
            $status = $request->get('status');
            $isBekerjaLain = $request->get('is_bekerja_di_tempat_lain');
            
            $query = DataRiwayatUnitKerjaLain::with(['pegawai']);

            // Filter by specific employee if provided
            if ($nikPegawai) {
                $query->where('nik_data_pegawai', $nikPegawai);
            }

            // Filter by nik_data_pegawai parameter (for compatibility)
            if ($request->get('nik_data_pegawai')) {
                $query->where('nik_data_pegawai', $request->get('nik_data_pegawai'));
            }

            // Filter by status
            if ($status) {
                $query->where('status', $status);
            }

            // Filter by is_bekerja_di_tempat_lain
            if ($isBekerjaLain !== null) {
                $query->where('is_bekerja_di_tempat_lain', (bool) $isBekerjaLain);
            }

            // Search functionality
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('jabatan', 'like', "%{$search}%")
                      ->orWhere('fungsi', 'like', "%{$search}%")
                      ->orWhereHas('pegawai', function ($subQ) use ($search) {
                          $subQ->where('nama_lengkap', 'like', "%{$search}%")
                               ->orWhere('nip', 'like', "%{$search}%");
                      });
                });
            }

            $dataRiwayatUnitKerjaLain = $query->orderBy('urut', 'asc')
                                            ->orderBy('created_at', 'desc')
                                            ->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Data riwayat unit kerja lain berhasil diambil',
                'data' => $dataRiwayatUnitKerjaLain
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data riwayat unit kerja lain',
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

            return response()->json([
                'success' => true,
                'message' => 'Form create riwayat unit kerja lain',
                'data' => [
                    'data_pegawai' => $dataPegawai,
                    'status_options' => $this->getStatusOptions(),
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
                'is_bekerja_di_tempat_lain' => 'required|boolean',
                'status' => 'required|string|in:aktif,tidak_aktif,selesai',
                'nama' => 'required|string|max:255',
                'jabatan' => 'required|string|max:255',
                'fungsi' => 'required|string|max:255',
                'urut' => 'required|integer|min:1|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();

            // Check for duplicate entry
            $exists = DataRiwayatUnitKerjaLain::where('nik_data_pegawai', $data['nik_data_pegawai'])
                                             ->where('nama', $data['nama'])
                                             ->where('jabatan', $data['jabatan'])
                                             ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data riwayat unit kerja lain dengan kombinasi yang sama sudah ada',
                ], 422);
            }

            $dataRiwayatUnitKerjaLain = DataRiwayatUnitKerjaLain::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Data riwayat unit kerja lain berhasil dibuat',
                'data' => $dataRiwayatUnitKerjaLain->load(['pegawai'])
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat data riwayat unit kerja lain',
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
            $dataRiwayatUnitKerjaLain = DataRiwayatUnitKerjaLain::with(['pegawai'])
                                                               ->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Data riwayat unit kerja lain berhasil diambil',
                'data' => $dataRiwayatUnitKerjaLain
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data riwayat unit kerja lain tidak ditemukan'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data riwayat unit kerja lain',
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
            $dataRiwayatUnitKerjaLain = DataRiwayatUnitKerjaLain::with(['pegawai'])
                                                               ->findOrFail($id);
            
            $dataPegawai = DataPegawai::select('nik', 'nip', 'nama_lengkap')
                                    ->orderBy('nama_lengkap')
                                    ->get();

            return response()->json([
                'success' => true,
                'message' => 'Form edit riwayat unit kerja lain',
                'data' => [
                    'data_riwayat_unit_kerja_lain' => $dataRiwayatUnitKerjaLain,
                    'data_pegawai' => $dataPegawai,
                    'status_options' => $this->getStatusOptions(),
                    'form_fields' => $this->getFormFields()
                ]
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data riwayat unit kerja lain tidak ditemukan'
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
            $dataRiwayatUnitKerjaLain = DataRiwayatUnitKerjaLain::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'nik_data_pegawai' => 'required|string|max:255|exists:data_pegawai,nik',
                'is_bekerja_di_tempat_lain' => 'required|boolean',
                'status' => 'required|string|in:aktif,tidak_aktif,selesai',
                'nama' => 'required|string|max:255',
                'jabatan' => 'required|string|max:255',
                'fungsi' => 'required|string|max:255',
                'urut' => 'required|integer|min:1|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();

            // Check for duplicate entry (excluding current record)
            $exists = DataRiwayatUnitKerjaLain::where('nik_data_pegawai', $data['nik_data_pegawai'])
                                             ->where('nama', $data['nama'])
                                             ->where('jabatan', $data['jabatan'])
                                             ->where('id', '!=', $id)
                                             ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data riwayat unit kerja lain dengan kombinasi yang sama sudah ada',
                ], 422);
            }

            $dataRiwayatUnitKerjaLain->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Data riwayat unit kerja lain berhasil diperbarui',
                'data' => $dataRiwayatUnitKerjaLain->load(['pegawai'])
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data riwayat unit kerja lain tidak ditemukan'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data riwayat unit kerja lain',
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
            $dataRiwayatUnitKerjaLain = DataRiwayatUnitKerjaLain::findOrFail($id);
            $dataRiwayatUnitKerjaLain->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data riwayat unit kerja lain berhasil dihapus'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data riwayat unit kerja lain tidak ditemukan'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data riwayat unit kerja lain',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get riwayat unit kerja lain by employee NIK
     *
     * @param string $nik
     * @return JsonResponse
     */
    public function getByEmployee(string $nik): JsonResponse
    {
        try {
            $dataRiwayatUnitKerjaLain = DataRiwayatUnitKerjaLain::where('nik_data_pegawai', $nik)
                                                               ->orderBy('urut', 'asc')
                                                               ->orderBy('created_at', 'desc')
                                                               ->get();

            return response()->json([
                'success' => true,
                'message' => 'Riwayat unit kerja lain pegawai berhasil diambil',
                'data' => $dataRiwayatUnitKerjaLain
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data riwayat unit kerja lain pegawai',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get status options
     *
     * @return array
     */
    private function getStatusOptions(): array
    {
        return [
            'aktif' => 'Aktif',
            'tidak_aktif' => 'Tidak Aktif',
            'selesai' => 'Selesai',
        ];
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
            'is_bekerja_di_tempat_lain' => [
                'type' => 'boolean',
                'label' => 'Bekerja di Tempat Lain',
                'required' => true,
                'validation' => 'required|boolean'
            ],
            'status' => [
                'type' => 'select',
                'label' => 'Status',
                'required' => true,
                'validation' => 'required|string|in:aktif,tidak_aktif,selesai'
            ],
            'nama' => [
                'type' => 'text',
                'label' => 'Nama Instansi/Perusahaan',
                'required' => true,
                'validation' => 'required|string|max:255'
            ],
            'jabatan' => [
                'type' => 'text',
                'label' => 'Jabatan',
                'required' => true,
                'validation' => 'required|string|max:255'
            ],
            'fungsi' => [
                'type' => 'text',
                'label' => 'Fungsi/Bidang Kerja',
                'required' => true,
                'validation' => 'required|string|max:255'
            ],
            'urut' => [
                'type' => 'number',
                'label' => 'Urutan',
                'required' => true,
                'validation' => 'required|integer|min:1|max:100'
            ]
        ];
    }
}