<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class IconsSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$file = public_path('bootstrap-icons.txt');

		$icons = file_get_contents($file);

		$icons = json_decode($icons, true);

		foreach ($icons as $icon) {
			DB::table('icons')->insert([
				'name' => $icon,
			]);
		}
	}
}
