<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$name = 'admin';

		DB::table('users')->insert([
			'name' => $name,
			'slug' => Str::slug($name),
			'email' => 'admin@jewepe.com',
			'role' => 'admin',
			'password' => Hash::make("12345678")
		]);
	}
}
