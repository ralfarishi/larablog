<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{
		$name = 'admin';

		DB::table('users')->insert([
			'name' => $name,
			'slug' => Str::slug($name),
			'email' => 'admin@jewepe.com',
			'role' => 'admin',
			'password' => Hash::make("12345")
		]);
	}
}
