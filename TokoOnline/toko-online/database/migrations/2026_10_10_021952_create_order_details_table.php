
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->string('id_order', 15);
            $table->string('id_barang', 10);
            $table->decimal('harga_satuan', 12, 2);
            $table->integer('Jumlah_beli');

            $table->primary(['id_order', 'id_barang']);

            $table->foreign('id_order')
                ->references('id_order')
                ->on('orders')
                ->onDelete('cascade');

            $table->foreign('id_barang')
                ->references('id_barang')
                ->on('products')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
 