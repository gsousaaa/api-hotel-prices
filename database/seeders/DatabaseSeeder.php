<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Company
        DB::table('companies')->updateOrInsert(
            ['cnpj' => '00.000.000/0001-00'],
            ['name' => 'Hotel teste', 'uf' => 'RN']
        );
        // busca a empresa criada
        $company = DB::table('companies')->where('cnpj', '00.000.000/0001-00')->first();

        // User
        DB::table('users')->updateOrInsert(
            ['email' => 'teste@gmail.com'],
            [
                'name' => 'User teste',
                'password' => '$2y$10$A/v0lagS3irP7.BTbxKDtu8K8WHIihM2TKDHVRFilcIDk3pxX6Fli', // senha já criptografada
                'role' => 'MANAGER',
                'company_id' => $company->id,
            ]
        );

        // Rooms
        $rooms = [
            ['name' => 'Quarto 101', 'type' => 'Standard'],
            ['name' => 'Quarto 102', 'type' => 'Deluxe'],
            ['name' => 'Quarto 103', 'type' => 'Suite'],
            ['name' => 'Quarto 104', 'type' => 'Standard'],
            ['name' => 'Quarto 105', 'type' => 'Deluxe'],
        ];

        foreach ($rooms as $room) {
            DB::table('rooms')->updateOrInsert(
                ['name' => $room['name'], 'company_id' => $company->id],
                [
                    'type' => $room['type'],
                    'company_id' => $company->id,
                    'created_at' => Carbon::now(),
                ]
            );
        }

        // ['name']: {id}
        $roomsIdMap = DB::table('rooms')
            ->where('company_id', $company->id)
            ->pluck('id', 'name');

        $prices = [
            ['Quarto 101', [[200.00, '2025-05-31'], [220.00, '2025-06-01']]],
            ['Quarto 102', [[300.00, '2025-05-31'], [310.00, '2025-06-01']]],
            ['Quarto 103', [[500.00, '2025-05-31'], [520.00, '2025-06-01']]],
            ['Quarto 104', [[190.00, '2025-05-31']]],
            ['Quarto 105', [[290.00, '2025-05-31'], [310.00, '2025-06-01']]],
        ];

        foreach ($prices as [$roomName, $roomPrices]) {
            // pega o id do quarto de acordo com o bome
            $roomId = $roomsIdMap[$roomName] ?? null;
            if (!$roomId) continue;

            foreach ($roomPrices as [$price, $date]) {
                DB::table('prices')->updateOrInsert(
                    ['room_id' => $roomId, 'effective_date' => $date],
                    [
                        'price' => $price,
                        'created_at' => Carbon::now(),
                    ]
                );
            }
        }
    }
}
