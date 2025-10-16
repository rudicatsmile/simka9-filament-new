<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

/**
 * Controller untuk mengelola data Users
 *
 * Menyediakan operasi CRUD dengan format respons JSON konsisten.
 */
class UserController extends Controller
{
    /**
     * Mengambil list user dengan pagination dan pencarian.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            $search = $request->get('search');

            $query = User::query();

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            $users = $query->orderBy('name', 'asc')->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Data users berhasil diambil',
                'data' => $users,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data users',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Informasi form create (mengikuti pola controller lain).
     *
     * @return JsonResponse
     */
    public function create(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Form create user',
            'data' => [
                'fields' => [
                    'name' => 'required|string|max:255',
                    'email' => 'required|email|max:255|unique:users',
                    'password' => 'required|string|min:8',
                ],
            ],
        ]);
    }

    /**
     * Menyimpan user baru.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ]);

            $user = User::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Data user berhasil disimpan',
                'data' => $user,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data user',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Menampilkan user tertentu.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Data user berhasil diambil',
                'data' => $user,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data user tidak ditemukan',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Informasi form edit user.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function edit(int $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Form edit user',
                'data' => [
                    'user' => $user,
                    'fields' => [
                        'name' => 'required|string|max:255',
                        'email' => 'required|email|max:255|unique:users,email,' . $id,
                        'password' => 'nullable|string|min:8',
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data user tidak ditemukan',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Memperbarui user.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . $id,
                'password' => 'nullable|string|min:8',
            ]);

            // Hanya update password jika diisi
            if (empty($validated['password'])) {
                unset($validated['password']);
            }

            $user->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Data user berhasil diperbarui',
                'data' => $user->fresh(),
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data user',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Menghapus user.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data user berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            // Mengikuti pola controller lain: non-existent -> 500
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data user',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}