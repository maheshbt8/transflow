<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = base_path() . '/database/seeds/currency.sql';
        $sql = file_get_contents($path);
        DB::unprepared($sql);
    }
}
