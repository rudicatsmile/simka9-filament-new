<?php

namespace App\Http\Controllers;

use App\Models\TabelJenisPelatihan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

/**
 * Controller untuk mengelola data jenis pelatihan
 * 
 * Controller ini menyediakan operasi CRUD untuk model TabelJenisPelatihan
 * dengan response format JSON yang konsisten
 * 
 * @package App\Http\Controllers
 * @author Laravel Filament
 * @version 1.0.0
 */
class JenisPelatihanController extends Controller
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

            $query = TabelJenisPelatihan::query();

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('kode', 'like', "%{$search}%")
                      ->orWhere('nama_jenis_pelatihan', 'like', "%{$search}%");
                });
            }

            $jenisPelatihan = $query->orderBy('urut', 'asc')->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Data jenis pelatihan berhasil diambil',
                'data' => $jenisPelatihan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data jenis pelatihan',
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
            'message' => 'Form create jenis pelatihan',
            'data' => [
                'fields' => [
                    'kode' => 'required|string|max:255|unique:tabel_jenis_pelatihan',
                    'nama_jenis_pelatihan' => 'required|string|max:255',
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
                'kode' => 'required|string|max:255|unique:tabel_jenis_pelatihan',
                'nama_jenis_pelatihan' => 'required|string|max:255',
                'status' => 'required|in:1,0',
                'urut' => 'required|integer|min:1'
            ]);

            $jenisPelatihan = TabelJenisPelatihan::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Data jenis pelatihan berhasil disimpan',
                'data' => $jenisPelatihan
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
                'message' => 'Gagal menyimpan data jenis pelatihan',
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
            $jenisPelatihan = TabelJenisPelatihan::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Data jenis pelatihan berhasil diambil',
                'data' => $jenisPelatihan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data jenis pelatihan tidak ditemukan',
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
            $jenisPelatihan = TabelJenisPelatihan::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Form edit jenis pelatihan',
                'data' => [
                    'jenis_pelatihan' => $jenisPelatihan,
                    'fields' => [
                        'kode' => 'required|string|max:255|unique:tabel_jenis_pelatihan,kode,' . $id,
                        'nama_jenis_pelatihan' => 'required|string|max:255',
                        'status' => 'required|in:1,0',
                        'urut' => 'required|integer|min:1'
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data jenis pelatihan tidak ditemukan',
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
            $jenisPelatihan = TabelJenisPelatihan::findOrFail($id);

            $validated = $request->validate([
                'kode' => 'required|string|max:255|unique:tabel_jenis_pelatihan,kode,' . $id,
                'nama_jenis_pelatihan' => 'required|string|max:255',
                'status' => 'required|in:1,0',
                'urut' => 'required|integer|min:1'
            ]);

            $jenisPelatihan->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Data jenis pelatihan berhasil diperbarui',
                'data' => $jenisPelatihan->fresh()
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
                'message' => 'Gagal memperbarui data jenis pelatihan',
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
            $jenisPelatihan = TabelJenisPelatihan::findOrFail($id);
            $jenisPelatihan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data jenis pelatihan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data jenis pelatihan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
