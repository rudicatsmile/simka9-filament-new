<?php

namespace App\Http\Controllers;

use App\Models\DataPegawai;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

/**
 * DataPegawaiController
 * 
 * Controller untuk mengelola data pegawai.
 * Menyediakan CRUD operations untuk model DataPegawai.
 * 
 * @package App\Http\Controllers
 * @author Laravel Filament
 * @version 1.0.0
 */
class DataPegawaiController extends Controller
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
            
            $query = DataPegawai::with([
                'unitKerja', 
                'agama', 
                'golonganDarah', 
                'statusNikah', 
                'statusKepegawaian',
                'propinsi',
                'propinsi2',
                'jabatanUtama',
                'jenjangPendidikan'
            ]);

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nip', 'like', "%{$search}%")
                      ->orWhere('nama_lengkap', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('no_tlp', 'like', "%{$search}%");
                });
            }

            $dataPegawai = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Data pegawai berhasil diambil',
                'data' => $dataPegawai
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data pegawai',
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
            'message' => 'Form create data pegawai',
            'data' => [
                'form_fields' => $this->getFormFields()
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
                'nip' => 'required|string|max:20|unique:data_pegawai,nip',
                'nik' => 'nullable|string|max:255',
                'kode_unit_kerja' => 'nullable|integer',
                'bpjs' => 'nullable|string|max:255',
                'npwp' => 'nullable|string|max:255',
                'nuptk' => 'nullable|string|max:255',
                'nama_lengkap' => 'required|string|max:100',
                'password' => 'required|string|min:6|max:100',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'tmp_lahir' => 'nullable|string|max:50',
                'tgl_lahir' => 'nullable|date',
                'jns_kelamin' => 'required|in:1,0',
                'kode_agama' => 'required|string|max:3',
                'kode_golongan_darah' => 'nullable|string|max:3',
                'kode_status_nikah' => 'nullable|string|max:3',
                'pstatus' => 'required|in:1,0',
                'kode_status_kepegawaian' => 'nullable|string|max:3',
                'blokir' => 'required|in:Tidak,Ya',
                'alamat' => 'nullable|string|max:100',
                'kode_propinsi' => 'nullable|string|max:3',
                'kodepos' => 'nullable|integer',
                'alamat2' => 'nullable|string|max:100',
                'kode_propinsi2' => 'nullable|string|max:3',
                'kodepos2' => 'nullable|integer',
                'email' => 'nullable|email|max:100|unique:data_pegawai,email',
                'no_tlp' => 'nullable|string|max:15',
                'hobi' => 'nullable|string|max:255',
                'tinggi_badan' => 'nullable|integer|min:100|max:250',
                'berat_badan' => 'nullable|integer|min:30|max:200',
                'kode_jabatan_utama' => 'nullable|string|max:3',
                'unit_fungsi' => 'nullable|string|max:255',
                'unit_tugas' => 'nullable|string|max:255',
                'unit_pelajaran' => 'nullable|string|max:100',
                'mulai_bekerja' => 'nullable|string|max:50',
                'kode_jenjang_pendidikan' => 'nullable|string|max:3',
                'program_studi' => 'nullable|string|max:50',
                'nama_kampus' => 'nullable|string|max:50',
                'tahun_lulus' => 'nullable|string|max:15',
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
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $filename = time() . '_' . $foto->getClientOriginalName();
                $path = $foto->storeAs('pegawai/foto', $filename, 'public');
                $data['foto'] = $path;
            }

            // Add audit fields
            $data['createdon'] = now();
            $data['createdby'] = auth()->user()->name ?? 'system';

            $dataPegawai = DataPegawai::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Data pegawai berhasil dibuat',
                'data' => $dataPegawai->load([
                    'unitKerja', 
                    'agama', 
                    'golonganDarah', 
                    'statusNikah', 
                    'statusKepegawaian',
                    'propinsi',
                    'propinsi2',
                    'jabatanUtama',
                    'jenjangPendidikan'
                ])
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat data pegawai',
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
            $dataPegawai = DataPegawai::with([
                'unitKerja', 
                'agama', 
                'golonganDarah', 
                'statusNikah', 
                'statusKepegawaian',
                'propinsi',
                'propinsi2',
                'jabatanUtama',
                'jenjangPendidikan'
            ])->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Data pegawai berhasil diambil',
                'data' => $dataPegawai
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data pegawai tidak ditemukan'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data pegawai',
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
            $dataPegawai = DataPegawai::with([
                'unitKerja', 
                'agama', 
                'golonganDarah', 
                'statusNikah', 
                'statusKepegawaian',
                'propinsi',
                'propinsi2',
                'jabatanUtama',
                'jenjangPendidikan'
            ])->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Form edit data pegawai',
                'data' => [
                    'pegawai' => $dataPegawai,
                    'form_fields' => $this->getFormFields()
                ]
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data pegawai tidak ditemukan'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data pegawai',
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
            $dataPegawai = DataPegawai::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'nip' => 'required|string|max:20|unique:data_pegawai,nip,' . $id,
                'nik' => 'nullable|string|max:255',
                'kode_unit_kerja' => 'nullable|integer',
                'bpjs' => 'nullable|string|max:255',
                'npwp' => 'nullable|string|max:255',
                'nuptk' => 'nullable|string|max:255',
                'nama_lengkap' => 'required|string|max:100',
                'password' => 'nullable|string|min:6|max:100',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'tmp_lahir' => 'nullable|string|max:50',
                'tgl_lahir' => 'nullable|date',
                'jns_kelamin' => 'required|in:1,0',
                'kode_agama' => 'required|string|max:3',
                'kode_golongan_darah' => 'nullable|string|max:3',
                'kode_status_nikah' => 'nullable|string|max:3',
                'pstatus' => 'required|in:1,0',
                'kode_status_kepegawaian' => 'nullable|string|max:3',
                'blokir' => 'required|in:Tidak,Ya',
                'alamat' => 'nullable|string|max:100',
                'kode_propinsi' => 'nullable|string|max:3',
                'kodepos' => 'nullable|integer',
                'alamat2' => 'nullable|string|max:100',
                'kode_propinsi2' => 'nullable|string|max:3',
                'kodepos2' => 'nullable|integer',
                'email' => 'nullable|email|max:100|unique:data_pegawai,email,' . $id,
                'no_tlp' => 'nullable|string|max:15',
                'hobi' => 'nullable|string|max:255',
                'tinggi_badan' => 'nullable|integer|min:100|max:250',
                'berat_badan' => 'nullable|integer|min:30|max:200',
                'kode_jabatan_utama' => 'nullable|string|max:3',
                'unit_fungsi' => 'nullable|string|max:255',
                'unit_tugas' => 'nullable|string|max:255',
                'unit_pelajaran' => 'nullable|string|max:100',
                'mulai_bekerja' => 'nullable|string|max:50',
                'kode_jenjang_pendidikan' => 'nullable|string|max:3',
                'program_studi' => 'nullable|string|max:50',
                'nama_kampus' => 'nullable|string|max:50',
                'tahun_lulus' => 'nullable|string|max:15',
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
            if ($request->hasFile('foto')) {
                // Delete old photo if exists
                if ($dataPegawai->foto && Storage::disk('public')->exists($dataPegawai->foto)) {
                    Storage::disk('public')->delete($dataPegawai->foto);
                }
                
                $foto = $request->file('foto');
                $filename = time() . '_' . $foto->getClientOriginalName();
                $path = $foto->storeAs('pegawai/foto', $filename, 'public');
                $data['foto'] = $path;
            }

            // Remove password from update if not provided
            if (empty($data['password'])) {
                unset($data['password']);
            }

            // Add audit fields
            $data['updatedon'] = now();
            $data['updatedby'] = auth()->user()->name ?? 'system';

            $dataPegawai->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Data pegawai berhasil diperbarui',
                'data' => $dataPegawai->load([
                    'unitKerja', 
                    'agama', 
                    'golonganDarah', 
                    'statusNikah', 
                    'statusKepegawaian',
                    'propinsi',
                    'propinsi2',
                    'jabatanUtama',
                    'jenjangPendidikan'
                ])
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data pegawai tidak ditemukan'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data pegawai',
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
            $dataPegawai = DataPegawai::findOrFail($id);
            
            // Delete photo if exists
            if ($dataPegawai->foto && Storage::disk('public')->exists($dataPegawai->foto)) {
                Storage::disk('public')->delete($dataPegawai->foto);
            }
            
            $dataPegawai->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data pegawai berhasil dihapus'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data pegawai tidak ditemukan'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data pegawai',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get form fields for create/edit forms
     *
     * @return array
     */
    private function getFormFields(): array
    {
        return [
            'jns_kelamin_options' => [
                '1' => 'Laki-laki',
                '0' => 'Perempuan'
            ],
            'pstatus_options' => [
                '1' => 'Aktif',
                '0' => 'Tidak Aktif'
            ],
            'blokir_options' => [
                'Tidak' => 'Tidak',
                'Ya' => 'Ya'
            ]
        ];
    }
}
