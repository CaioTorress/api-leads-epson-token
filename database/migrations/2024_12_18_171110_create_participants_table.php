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
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('postalcode');
            $table->string('address');
            $table->string('city');
            $table->string('district');
            $table->string('state');
            $table->string('number');
            $table->string('complement')->nullable();
            $table->string('note')->nullable();
            $table->string('document')->unique();
            $table->date('birthdate');
            $table->string('access_code');
            $table->enum('document_type', ['cpf','cnpj']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
