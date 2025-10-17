<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DataRiwayatAnak;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

/**
 * DataRiwayatAnakController
 * 
 * API Controller untuk mengelola data riwayat anak pegawai
 * Menyediakan RESTful endpoints dengan authorization dan validation
 * 
 * @package App\Http\Controllers\Api
 * @author SIMKA9 Development Team
 * @version 1.0.0
 */
class DataRiwayatAnakController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->authorizeResource(DataRiwayatAnak::class, 'data_riwayat_anak');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = DataRiwayatAnak::with([
                'pegawai:nik,nama,unit_kerja',
                'hubunganKeluarga:id,nama',
                'jenjangPendidikan:id,nama',
                'pekerjaan:id,nama'
            ]);

            // Filter by employee
            if ($request->filled('nik_pegawai')) {
                $query->where('nik_data_pegawai', $request->nik_pegawai);
            }

            // Filter by unit kerja
            if ($request->filled('unit_kerja')) {
                $query->whereHas('pegawai', function ($q) use ($request) {
                    $q->where('unit_kerja', $request->unit_kerja);
                });
            }

            // Filter by jenis kelamin
            if ($request->filled('jenis_kelamin')) {
                $query->where('jenis_kelamin', $request->jenis_kelamin);
            }

            // Filter by hubungan keluarga
            if ($request->filled('hubungan_keluarga_id')) {
                $query->where('id_tabel_hubungan_keluarga', $request->hubungan_keluarga_id);
            }

            // Filter by age range
            if ($request->filled('min_age') && $request->filled('max_age')) {
                $query->byAgeRange($request->min_age, $request->max_age);
            }

            // Search functionality
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('tempat_lahir', 'like', "%{$search}%")
                      ->orWhereHas('pegawai', function ($subQ) use ($search) {
                          $subQ->where('nama', 'like', "%{$search}%");
                      });
                });
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Pagination
            $perPage = min($request->get('per_page', 15), 100);
            $data = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Data riwayat anak berhasil diambil',
                'data' => $data->items(),
                'pagination' => [
                    'current_page' => $data->currentPage(),
                    'last_page' => $data->lastPage(),
                    'per_page' => $data->perPage(),
                    'total' => $data->total(),
                    'from' => $data->firstItem(),
                    'to' => $data->lastItem(),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data riwayat anak',
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
                'nik_data_pegawai' => 'required|string|max:20|exists:data_pegawai,nik',
                'nama' => 'required|string|max:255',
                'jenis_kelamin' => 'required|in:L,P',
                'tempat_lahir' => 'required|string|max:255',
                'tanggal_lahir' => 'required|date|before:today',
                'id_tabel_hubungan_keluarga' => 'required|exists:tabel_hubungan_keluarga,id',
                'id_jenjang_pendidikan' => 'nullable|exists:jenjang_pendidikan,id',
                'id_tabel_pekerjaan' => 'nullable|exists:tabel_pekerjaan,id',
                'keterangan' => 'nullable|string',
                'urut' => 'required|integer|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $dataRiwayatAnak = DataRiwayatAnak::create($validator->validated());

            $dataRiwayatAnak->load([
                'pegawai:nik,nama,unit_kerja',
                'hubunganKeluarga:id,nama',
                'jenjangPendidikan:id,nama',
                'pekerjaan:id,nama'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data riwayat anak berhasil dibuat',
                'data' => $dataRiwayatAnak
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat data riwayat anak',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param DataRiwayatAnak $dataRiwayatAnak
     * @return JsonResponse
     */
    public function show(DataRiwayatAnak $dataRiwayatAnak): JsonResponse
    {
        try {
            $dataRiwayatAnak->load([
                'pegawai:nik,nama,unit_kerja',
                'hubunganKeluarga:id,nama',
                'jenjangPendidikan:id,nama',
                'pekerjaan:id,nama'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data riwayat anak berhasil diambil',
                'data' => $dataRiwayatAnak
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data riwayat anak tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data riwayat anak',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param DataRiwayatAnak $dataRiwayatAnak
     * @return JsonResponse
     */
    public function update(Request $request, DataRiwayatAnak $dataRiwayatAnak): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'nik_data_pegawai' => 'sometimes|required|string|max:20|exists:data_pegawai,nik',
                'nama' => 'sometimes|required|string|max:255',
                'jenis_kelamin' => 'sometimes|required|in:L,P',
                'tempat_lahir' => 'sometimes|required|string|max:255',
                'tanggal_lahir' => 'sometimes|required|date|before:today',
                'id_tabel_hubungan_keluarga' => 'sometimes|required|exists:tabel_hubungan_keluarga,id',
                'id_jenjang_pendidikan' => 'nullable|exists:jenjang_pendidikan,id',
                'id_tabel_pekerjaan' => 'nullable|exists:tabel_pekerjaan,id',
                'keterangan' => 'nullable|string',
                'urut' => 'sometimes|required|integer|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $dataRiwayatAnak->update($validator->validated());

            $dataRiwayatAnak->load([
                'pegawai:nik,nama,unit_kerja',
                'hubunganKeluarga:id,nama',
                'jenjangPendidikan:id,nama',
                'pekerjaan:id,nama'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data riwayat anak berhasil diperbarui',
                'data' => $dataRiwayatAnak
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data riwayat anak tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data riwayat anak',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DataRiwayatAnak $dataRiwayatAnak
     * @return JsonResponse
     */
    public function destroy(DataRiwayatAnak $dataRiwayatAnak): JsonResponse
    {
        try {
            $dataRiwayatAnak->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data riwayat anak berhasil dihapus'
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data riwayat anak tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data riwayat anak',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get children by employee NIK
     *
     * @param Request $request
     * @param string $nik
     * @return JsonResponse
     */
    public function getByEmployee(Request $request, string $nik): JsonResponse
    {
        try {
            $query = DataRiwayatAnak::with([
                'pegawai:nik,nama,unit_kerja',
                'hubunganKeluarga:id,nama',
                'jenjangPendidikan:id,nama',
                'pekerjaan:id,nama'
            ])->byEmployee($nik);

            $data = $query->orderBy('urut')->get();

            return response()->json([
                'success' => true,
                'message' => 'Data anak pegawai berhasil diambil',
                'data' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data anak pegawai',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get children statistics by unit kerja
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getStatistics(Request $request): JsonResponse
    {
        try {
            $unitKerja = $request->get('unit_kerja');

            $query = DataRiwayatAnak::with('pegawai');

            if ($unitKerja) {
                $query->byUnitKerja($unitKerja);
            }

            $totalChildren = $query->count();
            $maleChildren = $query->clone()->byJenisKelamin('L')->count();
            $femaleChildren = $query->clone()->byJenisKelamin('P')->count();

            // Age distribution
            $ageGroups = [
                '0-5' => $query->clone()->byAgeRange(0, 5)->count(),
                '6-12' => $query->clone()->byAgeRange(6, 12)->count(),
                '13-17' => $query->clone()->byAgeRange(13, 17)->count(),
                '18+' => $query->clone()->byAgeRange(18, 100)->count(),
            ];

            return response()->json([
                'success' => true,
                'message' => 'Statistik data anak berhasil diambil',
                'data' => [
                    'total_children' => $totalChildren,
                    'male_children' => $maleChildren,
                    'female_children' => $femaleChildren,
                    'age_distribution' => $ageGroups,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik data anak',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
