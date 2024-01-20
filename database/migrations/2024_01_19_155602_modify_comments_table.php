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
		Schema::table('comments', function (Blueprint $table) {
			$table->dropColumn(['user_name', 'user_email']);

			$table->foreignId('user_id')->constrained('users')->onDelete('cascade')->after('post_id');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('comments', function (Blueprint $table) {
			$table->string('user_name')->nullable()->after('id');
			$table->string('user_email')->nullable()->after('user_name');
			$table->dropForeign(['user_id']);
			$table->dropColumn('user_id');
		});
	}
};