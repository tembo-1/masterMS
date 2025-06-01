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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('master_id')->constrained('users'); // Привязка к мастеру
            $table->string('name'); // Имя клиента
            $table->string('phone'); // Телефон
            $table->text('service'); // Услуга (например, "чистка котла")
            $table->date('next_date'); // Дата следующего ТО
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
