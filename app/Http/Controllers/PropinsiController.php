<?php

namespace App\Http\Controllers;

use App\Models\TabelPropinsi;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

/**
 * Controller untuk mengelola data propinsi
 * 
 * Controller ini menyediakan operasi CRUD untuk model TabelPropinsi
 * dengan response format JSON yang konsisten
 * 
 * @package App\Http\Controllers
 * @author Laravel Filament
 * @version 1.0.0
 */
class PropinsiController extends Controller
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

            $query = TabelPropinsi::query();

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('kode', 'like', "%{$search}%")
                      ->orWhere('nama_propinsi', 'like', "%{$search}%");
                });
            }

            $propinsi = $query->orderBy('urut', 'asc')->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Data propinsi berhasil diambil',
                'data' => $propinsi
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data propinsi',
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
            'message' => 'Form create propinsi',
            'data' => [
                'fields' => [
                    'kode' => 'required|string|max:255|unique:tabel_propinsi',
                    'nama_propinsi' => 'required|string|max:255',
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
                'kode' => 'required|string|max:255|unique:tabel_propinsi',
                'nama_propinsi' => 'required|string|max:255',
                'status' => 'required|in:1,0',
                'urut' => 'required|integer|min:1'
            ]);

            $propinsi = TabelPropinsi::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Data propinsi berhasil disimpan',
                'data' => $propinsi
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
                'message' => 'Gagal menyimpan data propinsi',
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
            $propinsi = TabelPropinsi::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Data propinsi berhasil diambil',
                'data' => $propinsi
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data propinsi tidak ditemukan',
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
            $propinsi = TabelPropinsi::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Form edit propinsi',
                'data' => [
                    'propinsi' => $propinsi,
                    'fields' => [
                        'kode' => 'required|string|max:255|unique:tabel_propinsi,kode,' . $id,
                        'nama_propinsi' => 'required|string|max:255',
                        'status' => 'required|in:1,0',
                        'urut' => 'required|integer|min:1'
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data propinsi tidak ditemukan',
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
            $propinsi = TabelPropinsi::findOrFail($id);

            $validated = $request->validate([
                'kode' => 'required|string|max:255|unique:tabel_propinsi,kode,' . $id,
                'nama_propinsi' => 'required|string|max:255',
                'status' => 'required|in:1,0',
                'urut' => 'required|integer|min:1'
            ]);

            $propinsi->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Data propinsi berhasil diperbarui',
                'data' => $propinsi->fresh()
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
                'message' => 'Gagal memperbarui data propinsi',
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
            $propinsi = TabelPropinsi::findOrFail($id);
            $propinsi->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data propinsi berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data propinsi',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
