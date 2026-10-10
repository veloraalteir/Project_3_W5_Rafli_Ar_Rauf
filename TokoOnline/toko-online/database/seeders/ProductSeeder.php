<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'id_barang' => 'BRG001',
                'nama_barang' => 'Buku Tulis',
                'deskripsi' => 'Buku tulis untuk kebutuhan sekolah dan kuliah.',
                'harga' => 5000,
                'stok' => 50,
                'gambar' => 'buku-tulis.jpg',
            ],
            [
                'id_barang' => 'BRG002',
                'nama_barang' => 'Pulpen',
                'deskripsi' => 'Pulpen untuk menulis sehari-hari.',
                'harga' => 3000,
                'stok' => 40,
                'gambar' => 'pulpen.jpg',
            ],
            [
                'id_barang' => 'BRG003',
                'nama_barang' => 'Pensil 2B',
                'deskripsi' => 'Pensil 2B untuk menulis dan menggambar.',
                'harga' => 2500,
                'stok' => 35,
                'gambar' => 'pensil-2b.jpg',
            ],
            [
                'id_barang' => 'BRG004',
                'nama_barang' => 'Penghapus',
                'deskripsi' => 'Penghapus untuk mengoreksi tulisan pensil.',
                'harga' => 2000,
                'stok' => 30,
                'gambar' => 'penghapus.jpg',
            ],
            [
                'id_barang' => 'BRG005',
                'nama_barang' => 'Penggaris',
                'deskripsi' => 'Penggaris untuk membantu mengukur dan membuat garis.',
                'harga' => 4000,
                'stok' => 25,
                'gambar' => 'penggaris.jpg',
            ],
            [
                'id_barang' => 'BRG006',
                'nama_barang' => 'Spidol',
                'deskripsi' => 'Spidol untuk menulis pada papan atau kertas.',
                'harga' => 7000,
                'stok' => 20,
                'gambar' => 'spidol.jpg',
            ],
            [
                'id_barang' => 'BRG007',
                'nama_barang' => 'Map Plastik',
                'deskripsi' => 'Map untuk menyimpan dokumen dan tugas.',
                'harga' => 3500,
                'stok' => 15,
                'gambar' => 'map-plastik.jpg',
            ],
            [
                'id_barang' => 'BRG008',
                'nama_barang' => 'Stabilo',
                'deskripsi' => 'Penanda teks untuk catatan dan materi belajar.',
                'harga' => 6000,
                'stok' => 18,
                'gambar' => 'stabilo.jpg',
            ],
            [
                'id_barang' => 'BRG009',
                'nama_barang' => 'Buku Gambar',
                'deskripsi' => 'Buku untuk menggambar dan membuat sketsa.',
                'harga' => 8000,
                'stok' => 12,
                'gambar' => 'buku-gambar.jpg',
            ],
            [
                'id_barang' => 'BRG010',
                'nama_barang' => 'Tempat Pensil',
                'deskripsi' => 'Tempat untuk menyimpan alat tulis.',
                'harga' => 15000,
                'stok' => 10,
                'gambar' => 'tempat-pensil.jpg',
            ],
        ]);
    }
}
