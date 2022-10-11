<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class SystemsettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$path = base_path() . '/database/seeds/settings.sql';
        $sql = file_get_contents($path);
        DB::unprepared($sql);

    }
}
