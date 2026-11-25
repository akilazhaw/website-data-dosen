<?php

namespace Database\Seeders;

use App\Models\Dosen;
use Illuminate\Database\Seeder;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $samples = [
            [
                'nidn' => '0005078504',
                'nama' => 'Keonho, S.T., M.Kom',
                'prodi' => 'Rekayasa Perangkat Lunak',
                'alamat' => 'Jl. jalan kita berdua',
            ],
            [
                'nidn' => '0011129002',
                'nama' => 'Mingyu Kim, M.Kom',
                'prodi' => 'Sains Data',
                'alamat' => 'Jl. ke ujung dunia',
            ],
            [
                'nidn' => '0023088703',
                'nama' => 'Dr. Jenoya, S.T., M.Kom',
                'prodi' => 'Sistem Informasi',
                'alamat' => 'Jl. Seoul No. 21',
            ],
            [
                'nidn' => '0015048201',
                'nama' => 'Amrisyah Pasaribu, S.Kom., M.M',
                'prodi' => 'Teknik Informatika',
                'alamat' => 'Jl. Sibolga No. 100',
            ],
        ];

        foreach ($samples as $sample) {
            Dosen::updateOrCreate(['nidn' => $sample['nidn']], $sample);
        }
    }
}
