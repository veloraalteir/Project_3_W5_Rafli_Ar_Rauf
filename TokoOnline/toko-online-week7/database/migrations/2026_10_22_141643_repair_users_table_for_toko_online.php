
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        try {
            Schema::dropIfExists('users');

            Schema::create('users', function (Blueprint $table) {
                $table->string('id_user', 15)->primary();
                $table->string('nama_lengkap', 100);
                $table->string('email', 100)->unique();
                $table->string('username', 50)->unique();
                $table->string('password', 255);
                $table->string('no_hp', 15);
                $table->text('alamat');
                $table->string('role', 20)->default('customer');
                $table->timestamps();
            });
        } finally {
            Schema::enableForeignKeyConstraints();
        }
    }

    public function down(): void
    {
        // Tidak menghapus tabel users agar migrasi rollback
        // tidak menghilangkan struktur pengguna yang dibutuhkan.
    }
};
