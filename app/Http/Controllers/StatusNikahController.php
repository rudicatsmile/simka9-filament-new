<?php

namespace App\Http\Controllers;

use App\Models\TabelStatusNikah;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

/**
 * Controller untuk mengelola data status nikah
 * 
 * Controller ini menyediakan operasi CRUD untuk model TabelStatusNikah
 * dengan response format JSON yang konsisten
 * 
 * @package App\Http\Controllers
 * @author Laravel Filament
 * @version 1.0.0
 */
class StatusNikahController extends Controller
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

            $query = TabelStatusNikah::query();

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('kode', 'like', "%{$search}%")
                      ->orWhere('nama_status_nikah', 'like', "%{$search}%");
                });
            }

            $statusNikah = $query->orderBy('urut', 'asc')->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Data status nikah berhasil diambil',
                'data' => $statusNikah
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data status nikah',
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
            'message' => 'Form create status nikah',
            'data' => [
                'fields' => [
                    'kode' => 'required|string|max:255|unique:tabel_status_nikahs',
                    'nama_status_nikah' => 'required|string|max:255',
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
                'kode' => 'required|string|max:255|unique:tabel_status_nikahs',
                'nama_status_nikah' => 'required|string|max:255',
                'status' => 'required|in:1,0',
                'urut' => 'required|integer|min:1'
            ]);

            $statusNikah = TabelStatusNikah::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Data status nikah berhasil disimpan',
                'data' => $statusNikah
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
                'message' => 'Gagal menyimpan data status nikah',
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
            $statusNikah = TabelStatusNikah::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Data status nikah berhasil diambil',
                'data' => $statusNikah
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data status nikah tidak ditemukan',
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
            $statusNikah = TabelStatusNikah::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Form edit status nikah',
                'data' => [
                    'status_nikah' => $statusNikah,
                    'fields' => [
                        'kode' => 'required|string|max:255|unique:tabel_status_nikahs,kode,' . $id,
                        'nama_status_nikah' => 'required|string|max:255',
                        'status' => 'required|in:1,0',
                        'urut' => 'required|integer|min:1'
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data status nikah tidak ditemukan',
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
            $statusNikah = TabelStatusNikah::findOrFail($id);

            $validated = $request->validate([
                'kode' => 'required|string|max:255|unique:tabel_status_nikahs,kode,' . $id,
                'nama_status_nikah' => 'required|string|max:255',
                'status' => 'required|in:1,0',
                'urut' => 'required|integer|min:1'
            ]);

            $statusNikah->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Data status nikah berhasil diperbarui',
                'data' => $statusNikah->fresh()
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
                'message' => 'Gagal memperbarui data status nikah',
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
            $statusNikah = TabelStatusNikah::findOrFail($id);
            $statusNikah->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data status nikah berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data status nikah',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
