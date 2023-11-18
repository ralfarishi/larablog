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
		Schema::table('login_history', function (Blueprint $table) {
			$table->boolean('status')->after('email')->default(false);
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('login_history', function (Blueprint $table) {
			$table->dropColumn('status');
		});
	}
};
