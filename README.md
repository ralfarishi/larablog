### Installation

1. Clone repo ini & install dependensi menggunakan composer.

```sh
$ cd sekolah-jewepe
$ composer install
```

2. Salin file .env.example.

```sh
$ cp .env.example .env
```

3. Buat database baru di MySQL dan masukkan nama database tersebut ke file .env.

```sh
DB_DATABASE=nama_database
DB_USERNAME=root
DB_PASSWORD=
```

4. Buka file seeder di lokasi `database/seeders/UserTableSeeder.php` untuk keperluan login admin.

```php
DB::table('users')->insert([
    'name' => 'admin', // nama
    'email' => 'admin@google.com', // email
    'password' => Hash::make("12345") // password
]);
```

5. Buat application key:

```sh
$ php artisan key:generate
```

6. Jalankan migrasi dan seed:

```sh
$ php artisan migrate --seed
```

7. Jalankan project:

```sh
$ php artisan serve
```
