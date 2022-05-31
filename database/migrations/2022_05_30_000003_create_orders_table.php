<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('payment_id');
            $table->string('quantity');
            $table->decimal('total');
            $table->date('date');
            $table
                ->enum('status', [
                    'Menunggu Konfirmasi',
                    'Pembayaran Dikonfirmasi',
                    'Menunggu Pembayaran',
                    'Dibatalkan',
                    'Selesai',
                ])
                ->default('Menunggu Pembayaran');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
