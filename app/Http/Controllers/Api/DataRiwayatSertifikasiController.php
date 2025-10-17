<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DataRiwayatSertifikasi;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

/**
 * DataRiwayatSertifikasiController
 * 
 * API Controller untuk mengelola data riwayat sertifikasi
 * Menyediakan RESTful endpoints dengan authorization dan validasi
 * 
 * @package App\Http\Controllers\Api
 * @author SIMKA9 Development Team
 * @version 1.0.0
 */
class DataRiwayatSertifikasiController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
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
            $this->authorize('viewAny', DataRiwayatSertifikasi::class);

            $query = DataRiwayatSertifikasi::with('pegawai');

            // Filter by employee
            if ($request->filled('nik_data_pegawai')) {
                $query->where('nik_data_pegawai', $request->nik_data_pegawai);
            }

            // Filter by certification status
            if ($request->filled('is_sertifikasi')) {
                $query->where('is_sertifikasi', $request->boolean('is_sertifikasi'));
            }

            // Filter by year
            if ($request->filled('tahun')) {
                $query->where('tahun', $request->tahun);
            }

            // Filter by inpasing year
            if ($request->filled('tahun_inpasing')) {
                $query->where('tahun_inpasing', $request->tahun_inpasing);
            }

            // Search functionality
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('nomor', 'like', "%{$search}%")
                      ->orWhere('induk_inpasing', 'like', "%{$search}%")
                      ->orWhere('sk_inpasing', 'like', "%{$search}%")
                      ->orWhereHas('pegawai', function ($pegawaiQuery) use ($search) {
                          $pegawaiQuery->where('nama', 'like', "%{$search}%")
                                      ->orWhere('nik', 'like', "%{$search}%");
                      });
                });
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'urut');
            $sortOrder = $request->get('sort_order', 'asc');
            $query->orderBy($sortBy, $sortOrder);

            // Pagination
            $perPage = min($request->get('per_page', 15), 100);
            $data = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Data riwayat sertifikasi berhasil diambil',
                'data' => $data->items(),
                'meta' => [
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
                'message' => 'Gagal mengambil data riwayat sertifikasi',
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
            $this->authorize('create', DataRiwayatSertifikasi::class);

            $validator = Validator::make($request->all(), [
                'nik_data_pegawai' => 'required|string|max:50|exists:data_pegawai,nik',
                'is_sertifikasi' => 'required|boolean',
                'nama' => 'nullable|string|max:255',
                'nomor' => 'nullable|string|max:255',
                'tahun' => 'nullable|integer|min:1900|max:' . (date('Y') + 10),
                'induk_inpasing' => 'nullable|string|max:255',
                'sk_inpasing' => 'nullable|string|max:255',
                'tahun_inpasing' => 'nullable|integer|min:1900|max:' . (date('Y') + 10),
                'berkas' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'urut' => 'required|integer|min:1',
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
                $data['berkas'] = $file->storeAs('sertifikasi', $filename, 'public');
            }

            $riwayatSertifikasi = DataRiwayatSertifikasi::create($data);
            $riwayatSertifikasi->load('pegawai');

            return response()->json([
                'success' => true,
                'message' => 'Data riwayat sertifikasi berhasil dibuat',
                'data' => $riwayatSertifikasi
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
                'message' => 'Gagal membuat data riwayat sertifikasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param DataRiwayatSertifikasi $dataRiwayatSertifikasi
     * @return JsonResponse
     */
    public function show(DataRiwayatSertifikasi $dataRiwayatSertifikasi): JsonResponse
    {
        try {
            $this->authorize('view', $dataRiwayatSertifikasi);

            $dataRiwayatSertifikasi->load('pegawai');

            return response()->json([
                'success' => true,
                'message' => 'Data riwayat sertifikasi berhasil diambil',
                'data' => $dataRiwayatSertifikasi
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data riwayat sertifikasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param DataRiwayatSertifikasi $dataRiwayatSertifikasi
     * @return JsonResponse
     */
    public function update(Request $request, DataRiwayatSertifikasi $dataRiwayatSertifikasi): JsonResponse
    {
        try {
            $this->authorize('update', $dataRiwayatSertifikasi);

            $validator = Validator::make($request->all(), [
                'nik_data_pegawai' => 'required|string|max:50|exists:data_pegawai,nik',
                'is_sertifikasi' => 'required|boolean',
                'nama' => 'nullable|string|max:255',
                'nomor' => 'nullable|string|max:255',
                'tahun' => 'nullable|integer|min:1900|max:' . (date('Y') + 10),
                'induk_inpasing' => 'nullable|string|max:255',
                'sk_inpasing' => 'nullable|string|max:255',
                'tahun_inpasing' => 'nullable|integer|min:1900|max:' . (date('Y') + 10),
                'berkas' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'urut' => 'required|integer|min:1',
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
                if ($dataRiwayatSertifikasi->berkas && Storage::disk('public')->exists($dataRiwayatSertifikasi->berkas)) {
                    Storage::disk('public')->delete($dataRiwayatSertifikasi->berkas);
                }

                $file = $request->file('berkas');
                $filename = time() . '_' . $file->getClientOriginalName();
                $data['berkas'] = $file->storeAs('sertifikasi', $filename, 'public');
            }

            $dataRiwayatSertifikasi->update($data);
            $dataRiwayatSertifikasi->load('pegawai');

            return response()->json([
                'success' => true,
                'message' => 'Data riwayat sertifikasi berhasil diperbarui',
                'data' => $dataRiwayatSertifikasi
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data riwayat sertifikasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DataRiwayatSertifikasi $dataRiwayatSertifikasi
     * @return JsonResponse
     */
    public function destroy(DataRiwayatSertifikasi $dataRiwayatSertifikasi): JsonResponse
    {
        try {
            $this->authorize('delete', $dataRiwayatSertifikasi);

            // Delete file if exists
            if ($dataRiwayatSertifikasi->berkas && Storage::disk('public')->exists($dataRiwayatSertifikasi->berkas)) {
                Storage::disk('public')->delete($dataRiwayatSertifikasi->berkas);
            }

            $dataRiwayatSertifikasi->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data riwayat sertifikasi berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data riwayat sertifikasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download berkas file
     *
     * @param DataRiwayatSertifikasi $dataRiwayatSertifikasi
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|JsonResponse
     */
    public function downloadBerkas(DataRiwayatSertifikasi $dataRiwayatSertifikasi)
    {
        try {
            $this->authorize('view', $dataRiwayatSertifikasi);

            if (!$dataRiwayatSertifikasi->berkas || !Storage::disk('public')->exists($dataRiwayatSertifikasi->berkas)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File tidak ditemukan'
                ], 404);
            }

            return Storage::disk('public')->download($dataRiwayatSertifikasi->berkas);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengunduh file',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get certification data by employee NIK
     *
     * @param Request $request
     * @param string $nik
     * @return JsonResponse
     */
    public function getByEmployee(Request $request, string $nik): JsonResponse
    {
        try {
            $this->authorize('viewAny', DataRiwayatSertifikasi::class);

            $query = DataRiwayatSertifikasi::with('pegawai')
                ->where('nik_data_pegawai', $nik);

            // Filter by certification status
            if ($request->filled('is_sertifikasi')) {
                $query->where('is_sertifikasi', $request->boolean('is_sertifikasi'));
            }

            // Filter by year
            if ($request->filled('tahun')) {
                $query->where('tahun', $request->tahun);
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'urut');
            $sortOrder = $request->get('sort_order', 'asc');
            $query->orderBy($sortBy, $sortOrder);

            $data = $query->get();

            return response()->json([
                'success' => true,
                'message' => 'Data riwayat sertifikasi pegawai berhasil diambil',
                'data' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data riwayat sertifikasi pegawai',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}