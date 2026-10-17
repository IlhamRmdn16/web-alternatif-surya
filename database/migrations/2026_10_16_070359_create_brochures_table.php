<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('brochures', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('slug')->unique();
            $t->string('description', 300)->nullable();
            $t->string('file');                                 // path di storage publik
            $t->unsignedBigInteger('file_size')->nullable();    // byte
            $t->unsignedInteger('sort')->default(0);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brochures');
    }
};
