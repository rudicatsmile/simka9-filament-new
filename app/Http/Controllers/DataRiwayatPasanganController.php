<?php

namespace App\Http\Controllers;

use App\Models\DataRiwayatPasangan;
use App\Models\DataPegawai;
use App\Models\JenjangPendidikan;
use App\Models\TabelPekerjaan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

/**
 * DataRiwayatPasanganController
 * 
 * Controller untuk mengelola data riwayat pasangan pegawai.
 * Menyediakan CRUD operations untuk model DataRiwayatPasangan.
 * 
 * @package App\Http\Controllers
 * @author Laravel Filament SIMKA9
 * @version 1.0.0
 */
class DataRiwayatPasanganController extends Controller
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
            
            $query = DataRiwayatPasangan::with(['dataPegawai', 'jenjangPendidikan', 'tabelPekerjaan']);

            // Filter by specific employee if provided
            if ($nikPegawai) {
                $query->where('nik_data_pegawai', $nikPegawai);
            }

            // Filter by nik_data_pegawai parameter (for compatibility)
            if ($request->get('nik_data_pegawai')) {
                $query->where('nik_data_pegawai', $request->get('nik_data_pegawai'));
            }

            // Search functionality
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_pasangan', 'like', "%{$search}%")
                      ->orWhere('tempat_lahir', 'like', "%{$search}%")
                      ->orWhere('hubungan', 'like', "%{$search}%")
                      ->orWhereHas('dataPegawai', function ($subQ) use ($search) {
                          $subQ->where('nama_lengkap', 'like', "%{$search}%")
                               ->orWhere('nip', 'like', "%{$search}%");
                      })
                      ->orWhereHas('jenjangPendidikan', function ($subQ) use ($search) {
                          $subQ->where('nama_jenjang_pendidikan', 'like', "%{$search}%");
                      })
                      ->orWhereHas('tabelPekerjaan', function ($subQ) use ($search) {
                          $subQ->where('nama_pekerjaan', 'like', "%{$search}%");
                      });
                });
            }

            $dataRiwayatPasangan = $query->orderBy('urut', 'asc')
                                        ->orderBy('tanggal_lahir', 'desc')
                                        ->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Data riwayat pasangan berhasil diambil',
                'data' => $dataRiwayatPasangan
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data riwayat pasangan',
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
            
            $jenjangPendidikan = JenjangPendidikan::where('status', '1')
                                                 ->orderBy('urut')
                                                 ->get();

            $tabelPekerjaan = TabelPekerjaan::where('status', '1')
                                           ->orderBy('nama_pekerjaan')
                                           ->get();

            return response()->json([
                'success' => true,
                'message' => 'Form create data riwayat pasangan',
                'data' => [
                    'data_pegawai' => $dataPegawai,
                    'jenjang_pendidikan' => $jenjangPendidikan,
                    'tabel_pekerjaan' => $tabelPekerjaan,
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
                'nama_pasangan' => 'required|string|max:255',
                'tempat_lahir' => 'required|string|max:255',
                'tanggal_lahir' => 'required|date|before:today',
                'hubungan' => 'required|in:Suami,Istri',
                'kode_jenjang_pendidikan' => 'nullable|string|max:10|exists:jenjang_pendidikan,kode',
                'kode_tabel_pekerjaan' => 'nullable|string|max:10|exists:tabel_pekerjaan,kode',
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
            $exists = DataRiwayatPasangan::where('nik_data_pegawai', $data['nik_data_pegawai'])
                                       ->where('nama_pasangan', $data['nama_pasangan'])
                                       ->where('tanggal_lahir', $data['tanggal_lahir'])
                                       ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data riwayat pasangan dengan kombinasi yang sama sudah ada',
                ], 422);
            }

            $dataRiwayatPasangan = DataRiwayatPasangan::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Data riwayat pasangan berhasil dibuat',
                'data' => $dataRiwayatPasangan->load(['dataPegawai', 'jenjangPendidikan', 'tabelPekerjaan'])
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat data riwayat pasangan',
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
            $dataRiwayatPasangan = DataRiwayatPasangan::with(['dataPegawai', 'jenjangPendidikan', 'tabelPekerjaan'])
                                                     ->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Data riwayat pasangan berhasil diambil',
                'data' => $dataRiwayatPasangan
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data riwayat pasangan tidak ditemukan'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data riwayat pasangan',
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
            $dataRiwayatPasangan = DataRiwayatPasangan::with(['dataPegawai', 'jenjangPendidikan', 'tabelPekerjaan'])
                                                     ->findOrFail($id);
            
            $dataPegawai = DataPegawai::select('nik', 'nip', 'nama_lengkap')
                                    ->orderBy('nama_lengkap')
                                    ->get();
            
            $jenjangPendidikan = JenjangPendidikan::where('status', '1')
                                                 ->orderBy('urut')
                                                 ->get();

            $tabelPekerjaan = TabelPekerjaan::where('status', '1')
                                           ->orderBy('nama_pekerjaan')
                                           ->get();

            return response()->json([
                'success' => true,
                'message' => 'Form edit data riwayat pasangan',
                'data' => [
                    'data_riwayat_pasangan' => $dataRiwayatPasangan,
                    'data_pegawai' => $dataPegawai,
                    'jenjang_pendidikan' => $jenjangPendidikan,
                    'tabel_pekerjaan' => $tabelPekerjaan,
                    'form_fields' => $this->getFormFields()
                ]
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data riwayat pasangan tidak ditemukan'
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
            $dataRiwayatPasangan = DataRiwayatPasangan::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'nik_data_pegawai' => 'required|string|max:255|exists:data_pegawai,nik',
                'nama_pasangan' => 'required|string|max:255',
                'tempat_lahir' => 'required|string|max:255',
                'tanggal_lahir' => 'required|date|before:today',
                'hubungan' => 'required|in:Suami,Istri',
                'kode_jenjang_pendidikan' => 'nullable|string|max:10|exists:jenjang_pendidikan,kode',
                'kode_tabel_pekerjaan' => 'nullable|string|max:10|exists:tabel_pekerjaan,kode',
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
            $exists = DataRiwayatPasangan::where('nik_data_pegawai', $data['nik_data_pegawai'])
                                       ->where('nama_pasangan', $data['nama_pasangan'])
                                       ->where('tanggal_lahir', $data['tanggal_lahir'])
                                       ->where('id', '!=', $id)
                                       ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data riwayat pasangan dengan kombinasi yang sama sudah ada',
                ], 422);
            }

            $dataRiwayatPasangan->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Data riwayat pasangan berhasil diperbarui',
                'data' => $dataRiwayatPasangan->load(['dataPegawai', 'jenjangPendidikan', 'tabelPekerjaan'])
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data riwayat pasangan tidak ditemukan'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data riwayat pasangan',
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
            $dataRiwayatPasangan = DataRiwayatPasangan::findOrFail($id);
            $dataRiwayatPasangan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data riwayat pasangan berhasil dihapus'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data riwayat pasangan tidak ditemukan'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data riwayat pasangan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get data riwayat pasangan by employee NIK
     *
     * @param string $nik
     * @return JsonResponse
     */
    public function getByEmployee(string $nik): JsonResponse
    {
        try {
            $dataRiwayatPasangan = DataRiwayatPasangan::with(['jenjangPendidikan', 'tabelPekerjaan'])
                                                     ->where('nik_data_pegawai', $nik)
                                                     ->orderBy('urut', 'asc')
                                                     ->orderBy('tanggal_lahir', 'desc')
                                                     ->get();

            return response()->json([
                'success' => true,
                'message' => 'Data riwayat pasangan pegawai berhasil diambil',
                'data' => $dataRiwayatPasangan
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data riwayat pasangan pegawai',
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
            'nama_pasangan' => [
                'type' => 'text',
                'label' => 'Nama Pasangan',
                'required' => true,
                'validation' => 'required|string|max:255'
            ],
            'tempat_lahir' => [
                'type' => 'text',
                'label' => 'Tempat Lahir',
                'required' => true,
                'validation' => 'required|string|max:255'
            ],
            'tanggal_lahir' => [
                'type' => 'date',
                'label' => 'Tanggal Lahir',
                'required' => true,
                'validation' => 'required|date|before:today'
            ],
            'hubungan' => [
                'type' => 'select',
                'label' => 'Hubungan',
                'required' => true,
                'options' => ['Suami', 'Istri'],
                'validation' => 'required|in:Suami,Istri'
            ],
            'kode_jenjang_pendidikan' => [
                'type' => 'select',
                'label' => 'Jenjang Pendidikan',
                'required' => false,
                'validation' => 'nullable|string|max:10|exists:jenjang_pendidikan,kode'
            ],
            'kode_tabel_pekerjaan' => [
                'type' => 'select',
                'label' => 'Pekerjaan',
                'required' => false,
                'validation' => 'nullable|string|max:10|exists:tabel_pekerjaan,kode'
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