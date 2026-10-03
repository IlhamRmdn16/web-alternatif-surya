<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('slug')->unique();
            $t->unsignedInteger('sort')->default(0);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });

        // 1 baris = 1 TIPE motor. Beberapa tipe dengan nama yang sama = 1 "Seri" (mis. Beat Series).
        Schema::create('motors', function (Blueprint $t) {
            $t->id();
            $t->foreignId('category_id')->constrained()->restrictOnDelete();
            $t->string('name');                 // nama seri, mis. "Beat"
            $t->string('series_slug')->index(); // slug nama seri, dasar pengelompokan
            $t->string('variant');              // nama tipe, mis. "CBS", "CBS ISS"
            $t->string('slug')->unique();       // URL halaman tipe, mis. beat-cbs
            $t->unsignedBigInteger('price')->default(0);         // harga OTR
            $t->unsignedBigInteger('cash_discount')->default(0); // diskon pembelian cash (Rp), 0 = tanpa diskon
            $t->string('image')->nullable();
            $t->text('description')->nullable();
            $t->boolean('show_on_home')->default(false);
            $t->unsignedInteger('home_sort')->default(0);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
            $t->unique(['series_slug', 'variant']);
        });

        Schema::create('motor_colors', function (Blueprint $t) {
            $t->id();
            $t->foreignId('motor_id')->constrained()->cascadeOnDelete();
            $t->string('name');
            $t->string('hex', 9)->default('#000000');
            $t->string('image')->nullable();
            // NULL = harga mengikuti tipe (otomatis). Terisi = harga khusus untuk warna ini.
            $t->unsignedBigInteger('price')->nullable();
            $t->unsignedBigInteger('cash_discount')->nullable();
            $t->unsignedInteger('sort')->default(0);
            $t->timestamps();
        });

        Schema::create('banners', function (Blueprint $t) {
            $t->id();
            $t->string('title')->nullable();
            $t->string('image');
            $t->string('link')->nullable();
            $t->unsignedInteger('sort')->default(0);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });

        Schema::create('faqs', function (Blueprint $t) {
            $t->id();
            $t->string('question');
            $t->text('answer');
            $t->unsignedInteger('sort')->default(0);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });

        Schema::create('promos', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('slug')->unique();
            $t->string('image')->nullable();
            $t->text('description')->nullable();
            $t->date('start_date')->nullable();
            $t->date('end_date')->nullable();
            $t->boolean('is_active')->default(true);
            $t->timestamps();
        });

        Schema::create('prospects', function (Blueprint $t) {
            $t->id();
            $t->string('source')->default('form'); // form | whatsapp
            $t->string('name');
            $t->string('address')->nullable();
            $t->string('phone')->nullable();
            $t->string('purpose')->nullable();
            $t->foreignId('motor_id')->nullable()->constrained()->nullOnDelete();
            $t->string('variant_name')->nullable();
            $t->string('color_name')->nullable();
            $t->unsignedBigInteger('dp')->nullable();
            $t->unsignedSmallInteger('tenor')->nullable();
            $t->text('message')->nullable();
            $t->string('status')->default('baru');
            $t->text('notes')->nullable();
            $t->timestamps();
        });

        Schema::create('settings', function (Blueprint $t) {
            $t->id();
            $t->string('key')->unique();
            $t->text('value')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['settings', 'prospects', 'promos', 'faqs', 'banners', 'motor_colors', 'motors', 'categories'] as $t) {
            Schema::dropIfExists($t);
        }
    }
};
