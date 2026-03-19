<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $nombres = [
            'Jose',
            'Maria',
            'Morelos',
            'Pavon'
        ];

        foreach ($nombres as $nombre){
           User::create([
                'name' => $nombre,
                'email' => strtolower($nombre) . '@ejemplo.com', // Agregamos el email
                'password' => bcrypt('12345678') // Agregamos el password
            ]);
        }
    }
}