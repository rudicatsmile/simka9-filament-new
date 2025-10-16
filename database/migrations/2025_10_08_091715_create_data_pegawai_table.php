<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('data_pegawai', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 20)->unique()->comment('Nomor Induk Pegawai');
            $table->string('nik', 255)->nullable()->comment('Nomor Induk Kependudukan');
            $table->string('kode_unit_kerja', 5)->nullable()->comment('Kode unit kerja');
            $table->string('bpjs', 255)->nullable()->comment('Nomor BPJS');
            $table->string('npwp', 255)->nullable()->comment('Nomor NPWP');
            $table->string('nuptk', 255)->nullable()->comment('Nomor NUPTK');
            $table->string('nama_lengkap', 100)->comment('Nama lengkap pegawai');
            $table->string('password', 100)->comment('Password pegawai');
            $table->string('foto', 100)->nullable()->comment('Path foto pegawai');
            $table->string('tmp_lahir', 50)->nullable()->comment('Tempat lahir');
            $table->date('tgl_lahir')->nullable()->comment('Tanggal lahir');
            $table->enum('jns_kelamin', ['1', '0'])->comment('Jenis kelamin: 1=Laki-laki, 0=Perempuan');
            $table->string('kode_agama', 3)->comment('Kode agama');
            $table->string('kode_golongan_darah', 3)->nullable()->comment('Kode golongan darah');
            $table->string('kode_status_nikah', 3)->nullable()->comment('Kode status nikah');
            $table->enum('pstatus', ['1', '0'])->default('1')->comment('Status pegawai: 1=Aktif, 0=Tidak Aktif');
            $table->string('kode_status_kepegawaian', 3)->default('PTY')->comment('Kode status kepegawaian');
            $table->enum('blokir', ['Tidak', 'Ya'])->default('Tidak')->comment('Status blokir');
            $table->string('alamat', 100)->nullable()->comment('Alamat utama');
            $table->string('kode_propinsi', 3)->nullable()->comment('Kode propinsi alamat utama');
            $table->integer('kodepos')->nullable()->comment('Kode pos alamat utama');
            $table->string('alamat2', 100)->nullable()->comment('Alamat kedua');
            $table->string('kode_propinsi2', 3)->nullable()->comment('Kode propinsi alamat kedua');
            $table->integer('kodepos2')->nullable()->comment('Kode pos alamat kedua');
            $table->string('email', 100)->nullable()->comment('Email pegawai');
            $table->string('no_tlp', 15)->nullable()->comment('Nomor telepon');
            $table->string('hobi', 255)->nullable()->comment('Hobi pegawai');
            $table->integer('tinggi_badan')->nullable()->comment('Tinggi badan dalam cm');
            $table->integer('berat_badan')->nullable()->comment('Berat badan dalam kg');
            $table->string('kode_jabatan_utama', 10)->nullable()->comment('Kode jabatan utama');
            $table->string('unit_fungsi', 255)->nullable()->comment('Unit fungsi');
            $table->string('unit_tugas', 255)->nullable()->comment('Unit tugas');
            $table->string('unit_pelajaran', 100)->nullable()->comment('Unit pelajaran');
            $table->date('mulai_bekerja')->nullable()->comment('Tanggal mulai bekerja');
            $table->string('kode_jenjang_pendidikan', 3)->nullable()->comment('Kode jenjang pendidikan');
            $table->string('program_studi', 50)->nullable()->comment('Program studi');
            $table->string('nama_kampus', 50)->nullable()->comment('Nama kampus');
            $table->string('tahun_lulus', 15)->nullable()->comment('Tahun lulus');
            $table->integer('login_attempts')->default(0)->comment('Jumlah percobaan login yang gagal');
            $table->timestamp('last_attempt')->nullable()->comment('Timestamp terakhir percobaan login');
            $table->timestamp('blocked_until')->nullable()->comment('Waktu hingga user dapat mencoba login kembali');
            $table->string('failed_ip', 45)->nullable()->comment('Alamat IP saat gagal login');
            $table->timestamp('createdon')->nullable()->comment('Tanggal dibuat');
            $table->string('createdby', 255)->nullable()->comment('Dibuat oleh');
            $table->timestamp('updatedon')->nullable()->comment('Tanggal diupdate');
            $table->string('updatedby', 255)->nullable()->comment('Diupdate oleh');

            // Indexes
            $table->index('kode_unit_kerja', 'fk_unit_kerja');
            $table->index('kode_agama', 'fk_agama');
            $table->index('kode_golongan_darah', 'fk_golongan_darah');
            $table->index('kode_status_nikah', 'fk_status_nikah');
            $table->index('kode_status_kepegawaian', 'fk_status_kepegawaian');
            $table->index('kode_propinsi', 'fk_propinsi');
            $table->index('kode_propinsi2', 'fk_propinsi2');
            $table->index('kode_jabatan_utama', 'fk_jabatan_utama');
            $table->index('kode_jenjang_pendidikan', 'fk_jenjang_pendidikan');
            $table->index('blocked_until', 'idx_pegawai_blocked_until');
            $table->index(['id', 'blocked_until'], 'idx_pegawai_username_blocked');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_pegawai');
    }
};
