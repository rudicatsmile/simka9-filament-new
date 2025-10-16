<?php

namespace App\Http\Controllers;

use App\Models\TabelGolonganDarah;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

/**
 * Controller untuk mengelola data golongan darah
 * 
 * Controller ini menyediakan operasi CRUD untuk model TabelGolonganDarah
 * dengan response format JSON yang konsisten
 * 
 * @package App\Http\Controllers
 * @author Laravel Filament
 * @version 1.0.0
 */
class GolonganDarahController extends Controller
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

            $query = TabelGolonganDarah::query();

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('kode', 'like', "%{$search}%")
                      ->orWhere('nama_golongan_darah', 'like', "%{$search}%");
                });
            }

            $golonganDarah = $query->orderBy('urut', 'asc')->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Data golongan darah berhasil diambil',
                'data' => $golonganDarah
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data golongan darah',
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
            'message' => 'Form create golongan darah',
            'data' => [
                'fields' => [
                    'kode' => 'required|string|max:255|unique:tabel_golongan_darah',
                    'nama_golongan_darah' => 'required|string|max:255',
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
                'kode' => 'required|string|max:255|unique:tabel_golongan_darah',
                'nama_golongan_darah' => 'required|string|max:255',
                'status' => 'required|in:1,0',
                'urut' => 'required|integer|min:1'
            ]);

            $golonganDarah = TabelGolonganDarah::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Data golongan darah berhasil disimpan',
                'data' => $golonganDarah
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
                'message' => 'Gagal menyimpan data golongan darah',
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
            $golonganDarah = TabelGolonganDarah::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Data golongan darah berhasil diambil',
                'data' => $golonganDarah
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data golongan darah tidak ditemukan',
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
            $golonganDarah = TabelGolonganDarah::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Form edit golongan darah',
                'data' => [
                    'golongan_darah' => $golonganDarah,
                    'fields' => [
                        'kode' => 'required|string|max:255|unique:tabel_golongan_darah,kode,' . $id,
                        'nama_golongan_darah' => 'required|string|max:255',
                        'status' => 'required|in:1,0',
                        'urut' => 'required|integer|min:1'
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data golongan darah tidak ditemukan',
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
            $golonganDarah = TabelGolonganDarah::findOrFail($id);

            $validated = $request->validate([
                'kode' => 'required|string|max:255|unique:tabel_golongan_darah,kode,' . $id,
                'nama_golongan_darah' => 'required|string|max:255',
                'status' => 'required|in:1,0',
                'urut' => 'required|integer|min:1'
            ]);

            $golonganDarah->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Data golongan darah berhasil diperbarui',
                'data' => $golonganDarah->fresh()
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
                'message' => 'Gagal memperbarui data golongan darah',
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
            $golonganDarah = TabelGolonganDarah::findOrFail($id);
            $golonganDarah->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data golongan darah berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data golongan darah',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
