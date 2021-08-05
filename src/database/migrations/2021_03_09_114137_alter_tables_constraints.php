<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTablesConstraints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Dùng để tạo khóa ngoại khi cấu trúc cơ sở dữ liệu ổn định
        // Schema::table('users', function (Blueprint $table) {
        //     $table->foreignId('role_id')->constrained('roles');
        // });
        // Schema::table('products', function (Blueprint $table) {
        //     $table->foreignId('status_product_id')->constrained('status_products');
        // });
        // Schema::table('orders', function (Blueprint $table) {
        //     $table->foreignId('status_order_id')->constrained('status_orders');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
