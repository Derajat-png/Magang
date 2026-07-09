<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('umkms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('business_type');
            $table->text('address');
            $table->string('phone', 20);
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'active', 'inactive'])->default('pending');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('umkm_id')->references('id')->on('umkms')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['umkm_id']);
        });
        Schema::dropIfExists('umkms');
    }
};
