<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DataRiwayatKepegawaian;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

/**
 * DataRiwayatKepegawaianController
 * 
 * API Controller untuk mengelola data riwayat kepegawaian
 * Menyediakan RESTful endpoints dengan authorization dan validasi
 * 
 * @package App\Http\Controllers\Api
 * @author SIMKA9 Development Team
 * @version 1.0.0
 */
class DataRiwayatKepegawaianController extends Controller
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
            $this->authorize('viewAny', DataRiwayatKepegawaian::class);

            $query = DataRiwayatKepegawaian::with('pegawai');

            // Filter by employee
            if ($request->filled('nik_data_pegawai')) {
                $query->where('nik_data_pegawai', $request->nik_data_pegawai);
            }

            // Search functionality
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('nomor', 'like', "%{$search}%")
                      ->orWhere('keterangan', 'like', "%{$search}%")
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
                'message' => 'Data riwayat kepegawaian berhasil diambil',
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
                'message' => 'Gagal mengambil data riwayat kepegawaian',
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
            $this->authorize('create', DataRiwayatKepegawaian::class);

            $validator = Validator::make($request->all(), [
                'nik_data_pegawai' => 'required|string|max:50|exists:data_pegawai,nik',
                'nama' => 'nullable|string|max:255',
                'tanggal_lahir' => 'nullable|date',
                'nomor' => 'nullable|string|max:255',
                'keterangan' => 'nullable|string',
                'berkas' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
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
                $data['berkas'] = $file->storeAs('kepegawaian', $filename, 'public');
            }

            $riwayatKepegawaian = DataRiwayatKepegawaian::create($data);
            $riwayatKepegawaian->load('pegawai');

            return response()->json([
                'success' => true,
                'message' => 'Data riwayat kepegawaian berhasil dibuat',
                'data' => $riwayatKepegawaian
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
                'message' => 'Gagal membuat data riwayat kepegawaian',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param DataRiwayatKepegawaian $dataRiwayatKepegawaian
     * @return JsonResponse
     */
    public function show(DataRiwayatKepegawaian $dataRiwayatKepegawaian): JsonResponse
    {
        try {
            $this->authorize('view', $dataRiwayatKepegawaian);

            $dataRiwayatKepegawaian->load('pegawai');

            return response()->json([
                'success' => true,
                'message' => 'Data riwayat kepegawaian berhasil diambil',
                'data' => $dataRiwayatKepegawaian
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data riwayat kepegawaian',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param DataRiwayatKepegawaian $dataRiwayatKepegawaian
     * @return JsonResponse
     */
    public function update(Request $request, DataRiwayatKepegawaian $dataRiwayatKepegawaian): JsonResponse
    {
        try {
            $this->authorize('update', $dataRiwayatKepegawaian);

            $validator = Validator::make($request->all(), [
                'nik_data_pegawai' => 'required|string|max:50|exists:data_pegawai,nik',
                'nama' => 'nullable|string|max:255',
                'tanggal_lahir' => 'nullable|date',
                'nomor' => 'nullable|string|max:255',
                'keterangan' => 'nullable|string',
                'berkas' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
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
                if ($dataRiwayatKepegawaian->berkas && Storage::disk('public')->exists($dataRiwayatKepegawaian->berkas)) {
                    Storage::disk('public')->delete($dataRiwayatKepegawaian->berkas);
                }

                $file = $request->file('berkas');
                $filename = time() . '_' . $file->getClientOriginalName();
                $data['berkas'] = $file->storeAs('kepegawaian', $filename, 'public');
            }

            $dataRiwayatKepegawaian->update($data);
            $dataRiwayatKepegawaian->load('pegawai');

            return response()->json([
                'success' => true,
                'message' => 'Data riwayat kepegawaian berhasil diperbarui',
                'data' => $dataRiwayatKepegawaian
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
                'message' => 'Gagal memperbarui data riwayat kepegawaian',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DataRiwayatKepegawaian $dataRiwayatKepegawaian
     * @return JsonResponse
     */
    public function destroy(DataRiwayatKepegawaian $dataRiwayatKepegawaian): JsonResponse
    {
        try {
            $this->authorize('delete', $dataRiwayatKepegawaian);

            // Delete file if exists
            if ($dataRiwayatKepegawaian->berkas && Storage::disk('public')->exists($dataRiwayatKepegawaian->berkas)) {
                Storage::disk('public')->delete($dataRiwayatKepegawaian->berkas);
            }

            $dataRiwayatKepegawaian->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data riwayat kepegawaian berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data riwayat kepegawaian',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download berkas file
     *
     * @param DataRiwayatKepegawaian $dataRiwayatKepegawaian
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|JsonResponse
     */
    public function downloadBerkas(DataRiwayatKepegawaian $dataRiwayatKepegawaian)
    {
        try {
            $this->authorize('view', $dataRiwayatKepegawaian);

            if (!$dataRiwayatKepegawaian->berkas || !Storage::disk('public')->exists($dataRiwayatKepegawaian->berkas)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File tidak ditemukan'
                ], 404);
            }

            return Storage::disk('public')->download($dataRiwayatKepegawaian->berkas);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengunduh file',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}