<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Halaman statis yang isinya diatur dari admin (Tentang Kami, Cara Beli & Syarat Kredit, Kebijakan Privasi)
        Schema::create('pages', function (Blueprint $t) {
            $t->id();
            $t->string('slug')->unique();
            $t->string('title');
            $t->string('meta_title')->nullable();
            $t->string('meta_description', 300)->nullable();
            $t->longText('content');
            $t->timestamps();
        });

        // Sales counter resmi (tampil di halaman Kontak & Lokasi)
        Schema::create('sales_contacts', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('position')->default('Sales Counter');
            $t->string('phone');
            $t->string('photo')->nullable();
            $t->unsignedInteger('sort')->default(0);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });

        // Mencatat sales tujuan saat konsumen klik chat WhatsApp sales tertentu
        Schema::table('prospects', function (Blueprint $t) {
            $t->string('sales_name')->nullable()->after('color_name');
        });
    }

    public function down(): void
    {
        Schema::table('prospects', function (Blueprint $t) {
            $t->dropColumn('sales_name');
        });
        Schema::dropIfExists('sales_contacts');
        Schema::dropIfExists('pages');
    }
};
