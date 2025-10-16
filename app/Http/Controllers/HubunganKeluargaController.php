<?php

namespace App\Http\Controllers;

use App\Models\TabelHubunganKeluarga;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

/**
 * Controller untuk mengelola data hubungan keluarga
 * 
 * Controller ini menyediakan operasi CRUD untuk model TabelHubunganKeluarga
 * dengan response format JSON yang konsisten
 */
class HubunganKeluargaController extends Controller
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

            $query = TabelHubunganKeluarga::query();

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('kode', 'like', "%{$search}%")
                      ->orWhere('nama_hubungan_keluarga', 'like', "%{$search}%");
                });
            }

            $hubunganKeluarga = $query->orderBy('urut', 'asc')->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Data hubungan keluarga berhasil diambil',
                'data' => $hubunganKeluarga
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data hubungan keluarga',
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
            'message' => 'Form create hubungan keluarga',
            'data' => [
                'fields' => [
                    'kode' => 'required|string|max:255|unique:tabel_hubungan_keluarga',
                    'nama_hubungan_keluarga' => 'required|string|max:255',
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
                'kode' => 'required|string|max:255|unique:tabel_hubungan_keluarga',
                'nama_hubungan_keluarga' => 'required|string|max:255',
                'status' => 'required|in:1,0',
                'urut' => 'required|integer|min:1'
            ]);

            $hubunganKeluarga = TabelHubunganKeluarga::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Data hubungan keluarga berhasil disimpan',
                'data' => $hubunganKeluarga
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
                'message' => 'Gagal menyimpan data hubungan keluarga',
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
            $hubunganKeluarga = TabelHubunganKeluarga::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Data hubungan keluarga berhasil diambil',
                'data' => $hubunganKeluarga
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data hubungan keluarga tidak ditemukan',
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
            $hubunganKeluarga = TabelHubunganKeluarga::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Form edit hubungan keluarga',
                'data' => [
                    'hubungan_keluarga' => $hubunganKeluarga,
                    'fields' => [
                        'kode' => 'required|string|max:255|unique:tabel_hubungan_keluarga,kode,' . $id,
                        'nama_hubungan_keluarga' => 'required|string|max:255',
                        'status' => 'required|in:1,0',
                        'urut' => 'required|integer|min:1'
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data hubungan keluarga tidak ditemukan',
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
            $hubunganKeluarga = TabelHubunganKeluarga::findOrFail($id);

            $validated = $request->validate([
                'kode' => 'required|string|max:255|unique:tabel_hubungan_keluarga,kode,' . $id,
                'nama_hubungan_keluarga' => 'required|string|max:255',
                'status' => 'required|in:1,0',
                'urut' => 'required|integer|min:1'
            ]);

            $hubunganKeluarga->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Data hubungan keluarga berhasil diperbarui',
                'data' => $hubunganKeluarga->fresh()
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
                'message' => 'Gagal memperbarui data hubungan keluarga',
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
            $hubunganKeluarga = TabelHubunganKeluarga::findOrFail($id);
            $hubunganKeluarga->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data hubungan keluarga berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data hubungan keluarga',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
