<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->date('payment_date');
            $table->string('py_no');
            $table->foreignId('department_id');
            $table->enum('currency', ['USD', 'MMK']);
            $table->float('amount',12);
            $table->float('ct')->default('0');
            $table->string('supplier');
            $table->string('ac_name');
            $table->string('description');
            $table->string('settle_by');
            $table->boolean('paid_status')->default(0);
            $table->foreignId('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
