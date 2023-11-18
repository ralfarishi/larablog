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
		Schema::create('login_history', function (Blueprint $table) {
			$table->id();
			$table->string('email');
			$table->string('ip_address');
			$table->text('user_agent');
			$table->string('city')->nullable();
			$table->decimal('latitude', 10, 8)->nullable();
			$table->decimal('longitude', 11, 8)->nullable();
			$table->timestamp('created_at')->useCurrent();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('login_history');
	}
};
