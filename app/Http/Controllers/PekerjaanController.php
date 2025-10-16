<?php

namespace App\Http\Controllers;

use App\Models\TabelPekerjaan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

/**
 * Controller untuk mengelola data pekerjaan
 * 
 * Controller ini menyediakan operasi CRUD untuk model TabelPekerjaan
 * dengan response format JSON yang konsisten
 */
class PekerjaanController extends Controller
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

            $query = TabelPekerjaan::query();

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('kode', 'like', "%{$search}%")
                      ->orWhere('nama_pekerjaan', 'like', "%{$search}%");
                });
            }

            $pekerjaan = $query->orderBy('urut', 'asc')->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Data pekerjaan berhasil diambil',
                'data' => $pekerjaan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data pekerjaan',
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
        return response()->json([
            'success' => true,
            'message' => 'Form create pekerjaan',
            'data' => [
                'fields' => [
                    'kode' => 'required|string|max:255|unique:tabel_pekerjaan',
                    'nama_pekerjaan' => 'required|string|max:255',
                    'status' => 'required|in:1,0',
                    'urut' => 'required|integer|min:1'
                ]
            ]
        ]);
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
            $validated = $request->validate([
                'kode' => 'required|string|max:255|unique:tabel_pekerjaan',
                'nama_pekerjaan' => 'required|string|max:255',
                'status' => 'required|in:1,0',
                'urut' => 'required|integer|min:1'
            ]);

            $pekerjaan = TabelPekerjaan::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Data pekerjaan berhasil disimpan',
                'data' => $pekerjaan
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
                'message' => 'Gagal menyimpan data pekerjaan',
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
            $pekerjaan = TabelPekerjaan::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Data pekerjaan berhasil diambil',
                'data' => $pekerjaan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data pekerjaan tidak ditemukan',
                'error' => $e->getMessage()
            ], 404);
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
            $pekerjaan = TabelPekerjaan::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Form edit pekerjaan',
                'data' => [
                    'pekerjaan' => $pekerjaan,
                    'fields' => [
                        'kode' => 'required|string|max:255|unique:tabel_pekerjaan,kode,' . $id,
                        'nama_pekerjaan' => 'required|string|max:255',
                        'status' => 'required|in:1,0',
                        'urut' => 'required|integer|min:1'
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data pekerjaan tidak ditemukan',
                'error' => $e->getMessage()
            ], 404);
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
            $pekerjaan = TabelPekerjaan::findOrFail($id);

            $validated = $request->validate([
                'kode' => 'required|string|max:255|unique:tabel_pekerjaan,kode,' . $id,
                'nama_pekerjaan' => 'required|string|max:255',
                'status' => 'required|in:1,0',
                'urut' => 'required|integer|min:1'
            ]);

            $pekerjaan->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Data pekerjaan berhasil diperbarui',
                'data' => $pekerjaan->fresh()
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
                'message' => 'Gagal memperbarui data pekerjaan',
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
            $pekerjaan = TabelPekerjaan::findOrFail($id);
            $pekerjaan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data pekerjaan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data pekerjaan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
