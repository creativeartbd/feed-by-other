<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        //$this->call(MysqlServersSeeder::class);
        $this->call(CountriesSeeder::class);
        $this->call(CurrenciesSeeder::class);
        $this->call(TimeZonesSeeder::class);
        $this->call(XmlFieldsSeeder::class);
        //$this->call(MongodbServers::class);
    }
}
