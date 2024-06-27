<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('programs')->insert([
            ['sumber_dana' => 'Zakat', 'program' => 'Zakat Fitrah', 'keterangan' => 'Ada'],
            ['sumber_dana' => 'Zakat', 'program' => 'Zakat Penghasilan', 'keterangan' => 'Ada'],
            ['sumber_dana' => 'Infaq Shodaqoh Terikat', 'program' => 'Berbagi Pendidikan SMP', 'keterangan' => 'Ada'],
            ['sumber_dana' => 'Infaq Shodaqoh Tidak Terikat', 'program' => 'Charity Box Personal', 'keterangan' => 'Tidak ada'],
        ]);
    }
}
