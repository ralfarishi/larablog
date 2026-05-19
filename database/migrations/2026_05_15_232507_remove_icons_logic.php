<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::dropIfExists('icons');
    Schema::table('categories', function (Blueprint $table) {
      $table->dropColumn('icon');
    });
  }

  public function down(): void
  {
    Schema::create('icons', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->timestamps();
    });

    Schema::table('categories', function (Blueprint $table) {
      $table->string('icon')->nullable();
    });
  }
};
