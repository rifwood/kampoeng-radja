<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $wahana = Category::firstOrCreate(['slug' => 'wahana'], ['name' => 'Wahana']);
        Category::firstOrCreate(['slug' => 'tempat-makan'], ['name' => 'Tempat Makan']);

        foreach (['Anak-anak', 'Dewasa', 'Air', 'Darat', 'Adrenaline', 'Santai'] as $label) {
            $wahana->labels()->firstOrCreate(
                ['slug' => str($label)->slug()->toString()],
                ['name' => $label],
            );
        }
    }
}
