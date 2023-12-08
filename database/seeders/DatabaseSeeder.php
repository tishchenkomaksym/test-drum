<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         User::factory(1)->create([
                'name' => 'Maks_test',
                'email' => 'test@mail.ua',
                'password' => Hash::make('test_test'),
             ]);
         User::factory(20)->create();

        $this->call([
            TaskSeeder::class,
        ]);
    }
}
