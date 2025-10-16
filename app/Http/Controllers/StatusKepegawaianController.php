<?php

namespace App\Http\Controllers;

use App\Models\TabelStatusKepegawaian;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * Controller untuk mengelola data status kepegawaian
 * 
 * @package App\Http\Controllers
 */
class StatusKepegawaianController extends Controller
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
            $query = TabelStatusKepegawaian::query();

            // Search functionality
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('kode', 'like', "%{$search}%")
                      ->orWhere('nama_status_kepegawaian', 'like', "%{$search}%");
                });
            }

            // Filter by status
            if ($request->has('status') && in_array($request->status, ['1', '0'])) {
                $query->where('status', $request->status);
            }

            // Ordering
            $sortBy = $request->get('sort_by', 'urut');
            $sortOrder = $request->get('sort_order', 'asc');
            
            if (in_array($sortBy, ['kode', 'nama_status_kepegawaian', 'status', 'urut', 'created_at'])) {
                $query->orderBy($sortBy, $sortOrder);
            }

            // Pagination
            $perPage = $request->get('per_page', 15);
            $perPage = min($perPage, 100); // Limit max per page
            
            $statusKepegawaian = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Data status kepegawaian berhasil diambil',
                'data' => $statusKepegawaian
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data status kepegawaian',
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
            'message' => 'Form create status kepegawaian',
            'data' => [
                'fields' => [
                    'kode' => 'required|string|max:255|unique:tabel_status_kepegawaians',
                    'nama_status_kepegawaian' => 'required|string|max:255',
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
            $validator = Validator::make($request->all(), [
                'kode' => 'required|string|max:255|unique:tabel_status_kepegawaians,kode',
                'nama_status_kepegawaian' => 'required|string|max:255',
                'status' => 'required|in:1,0',
                'urut' => 'required|integer|min:1'
            ], [
                'kode.required' => 'Kode status kepegawaian wajib diisi',
                'kode.unique' => 'Kode status kepegawaian sudah digunakan',
                'nama_status_kepegawaian.required' => 'Nama status kepegawaian wajib diisi',
                'status.required' => 'Status wajib dipilih',
                'status.in' => 'Status harus berupa 1 (aktif) atau 0 (tidak aktif)',
                'urut.required' => 'Urutan wajib diisi',
                'urut.integer' => 'Urutan harus berupa angka',
                'urut.min' => 'Urutan minimal 1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $statusKepegawaian = TabelStatusKepegawaian::create($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Status kepegawaian berhasil dibuat',
                'data' => $statusKepegawaian
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat status kepegawaian',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        try {
            $statusKepegawaian = TabelStatusKepegawaian::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Detail status kepegawaian berhasil diambil',
                'data' => $statusKepegawaian
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Status kepegawaian tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail status kepegawaian',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function edit(string $id): JsonResponse
    {
        try {
            $statusKepegawaian = TabelStatusKepegawaian::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Form edit status kepegawaian',
                'data' => [
                    'status_kepegawaian' => $statusKepegawaian,
                    'fields' => [
                        'kode' => 'required|string|max:255|unique:tabel_status_kepegawaians,kode,' . $id,
                        'nama_status_kepegawaian' => 'required|string|max:255',
                        'status' => 'required|in:1,0',
                        'urut' => 'required|integer|min:1'
                    ]
                ]
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Status kepegawaian tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil form edit status kepegawaian',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $statusKepegawaian = TabelStatusKepegawaian::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'kode' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('tabel_status_kepegawaians', 'kode')->ignore($id)
                ],
                'nama_status_kepegawaian' => 'required|string|max:255',
                'status' => 'required|in:1,0',
                'urut' => 'required|integer|min:1'
            ], [
                'kode.required' => 'Kode status kepegawaian wajib diisi',
                'kode.unique' => 'Kode status kepegawaian sudah digunakan',
                'nama_status_kepegawaian.required' => 'Nama status kepegawaian wajib diisi',
                'status.required' => 'Status wajib dipilih',
                'status.in' => 'Status harus berupa 1 (aktif) atau 0 (tidak aktif)',
                'urut.required' => 'Urutan wajib diisi',
                'urut.integer' => 'Urutan harus berupa angka',
                'urut.min' => 'Urutan minimal 1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $statusKepegawaian->update($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Status kepegawaian berhasil diperbarui',
                'data' => $statusKepegawaian->fresh()
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Status kepegawaian tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status kepegawaian',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $statusKepegawaian = TabelStatusKepegawaian::findOrFail($id);
            $statusKepegawaian->delete();

            return response()->json([
                'success' => true,
                'message' => 'Status kepegawaian berhasil dihapus'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Status kepegawaian tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus status kepegawaian',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
