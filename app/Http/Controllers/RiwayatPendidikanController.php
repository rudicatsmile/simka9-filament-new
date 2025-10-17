<?php

namespace App\Http\Controllers;

use App\Models\RiwayatPendidikan;
use App\Models\DataPegawai;
use App\Models\JenjangPendidikan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

/**
 * RiwayatPendidikanController
 * 
 * Controller untuk mengelola data riwayat pendidikan pegawai.
 * Menyediakan CRUD operations untuk model RiwayatPendidikan.
 * 
 * @package App\Http\Controllers
 * @author Laravel Filament SIMKA9
 * @version 1.0.0
 */
class RiwayatPendidikanController extends Controller
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
            
            $query = RiwayatPendidikan::with(['dataPegawai', 'jenjangPendidikan']);

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
                    $q->where('nama_sekolah', 'like', "%{$search}%")
                      ->orWhere('tahun_ijazah', 'like', "%{$search}%")
                      ->orWhereHas('dataPegawai', function ($subQ) use ($search) {
                          $subQ->where('nama_lengkap', 'like', "%{$search}%")
                               ->orWhere('nip', 'like', "%{$search}%");
                      })
                      ->orWhereHas('jenjangPendidikan', function ($subQ) use ($search) {
                          $subQ->where('nama_jenjang_pendidikan', 'like', "%{$search}%");
                      });
                });
            }

            $riwayatPendidikan = $query->orderBy('urut', 'asc')
                                      ->orderBy('tahun_ijazah', 'desc')
                                      ->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Data riwayat pendidikan berhasil diambil',
                'data' => $riwayatPendidikan
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data riwayat pendidikan',
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

            return response()->json([
                'success' => true,
                'message' => 'Form create riwayat pendidikan',
                'data' => [
                    'data_pegawai' => $dataPegawai,
                    'jenjang_pendidikan' => $jenjangPendidikan,
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
                'kode_jenjang_pendidikan' => 'required|string|max:10|exists:jenjang_pendidikan,kode',
                'nama_sekolah' => 'required|string|max:255',
                'tahun_ijazah' => 'required|integer|min:1900|max:' . (date('Y') + 10),
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
            $exists = RiwayatPendidikan::where('nik_data_pegawai', $data['nik_data_pegawai'])
                                     ->where('kode_jenjang_pendidikan', $data['kode_jenjang_pendidikan'])
                                     ->where('nama_sekolah', $data['nama_sekolah'])
                                     ->where('tahun_ijazah', $data['tahun_ijazah'])
                                     ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data riwayat pendidikan dengan kombinasi yang sama sudah ada',
                ], 422);
            }

            $riwayatPendidikan = RiwayatPendidikan::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Data riwayat pendidikan berhasil dibuat',
                'data' => $riwayatPendidikan->load(['dataPegawai', 'jenjangPendidikan'])
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat data riwayat pendidikan',
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
            $riwayatPendidikan = RiwayatPendidikan::with(['dataPegawai', 'jenjangPendidikan'])
                                                 ->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Data riwayat pendidikan berhasil diambil',
                'data' => $riwayatPendidikan
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data riwayat pendidikan tidak ditemukan'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data riwayat pendidikan',
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
            $riwayatPendidikan = RiwayatPendidikan::with(['dataPegawai', 'jenjangPendidikan'])
                                                 ->findOrFail($id);
            
            $dataPegawai = DataPegawai::select('nik', 'nip', 'nama_lengkap')
                                    ->orderBy('nama_lengkap')
                                    ->get();
            
            $jenjangPendidikan = JenjangPendidikan::where('status', '1')
                                                 ->orderBy('urut')
                                                 ->get();

            return response()->json([
                'success' => true,
                'message' => 'Form edit riwayat pendidikan',
                'data' => [
                    'riwayat_pendidikan' => $riwayatPendidikan,
                    'data_pegawai' => $dataPegawai,
                    'jenjang_pendidikan' => $jenjangPendidikan,
                    'form_fields' => $this->getFormFields()
                ]
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data riwayat pendidikan tidak ditemukan'
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
            $riwayatPendidikan = RiwayatPendidikan::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'nik_data_pegawai' => 'required|string|max:255|exists:data_pegawai,nik',
                'kode_jenjang_pendidikan' => 'required|string|max:10|exists:jenjang_pendidikan,kode',
                'nama_sekolah' => 'required|string|max:255',
                'tahun_ijazah' => 'required|integer|min:1900|max:' . (date('Y') + 10),
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
            $exists = RiwayatPendidikan::where('nik_data_pegawai', $data['nik_data_pegawai'])
                                     ->where('kode_jenjang_pendidikan', $data['kode_jenjang_pendidikan'])
                                     ->where('nama_sekolah', $data['nama_sekolah'])
                                     ->where('tahun_ijazah', $data['tahun_ijazah'])
                                     ->where('id', '!=', $id)
                                     ->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data riwayat pendidikan dengan kombinasi yang sama sudah ada',
                ], 422);
            }

            $riwayatPendidikan->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Data riwayat pendidikan berhasil diperbarui',
                'data' => $riwayatPendidikan->load(['dataPegawai', 'jenjangPendidikan'])
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data riwayat pendidikan tidak ditemukan'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data riwayat pendidikan',
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
            $riwayatPendidikan = RiwayatPendidikan::findOrFail($id);
            $riwayatPendidikan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data riwayat pendidikan berhasil dihapus'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data riwayat pendidikan tidak ditemukan'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data riwayat pendidikan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get riwayat pendidikan by employee NIK
     *
     * @param string $nik
     * @return JsonResponse
     */
    public function getByEmployee(string $nik): JsonResponse
    {
        try {
            $riwayatPendidikan = RiwayatPendidikan::with(['jenjangPendidikan'])
                                                 ->where('nik_data_pegawai', $nik)
                                                 ->orderBy('urut', 'asc')
                                                 ->orderBy('tahun_ijazah', 'desc')
                                                 ->get();

            return response()->json([
                'success' => true,
                'message' => 'Riwayat pendidikan pegawai berhasil diambil',
                'data' => $riwayatPendidikan
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data riwayat pendidikan pegawai',
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
            'kode_jenjang_pendidikan' => [
                'type' => 'select',
                'label' => 'Jenjang Pendidikan',
                'required' => true,
                'validation' => 'required|string|max:10|exists:jenjang_pendidikan,kode'
            ],
            'nama_sekolah' => [
                'type' => 'text',
                'label' => 'Nama Sekolah/Institusi',
                'required' => true,
                'validation' => 'required|string|max:255'
            ],
            'tahun_ijazah' => [
                'type' => 'number',
                'label' => 'Tahun Ijazah',
                'required' => true,
                'validation' => 'required|integer|min:1900|max:' . (date('Y') + 10)
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
