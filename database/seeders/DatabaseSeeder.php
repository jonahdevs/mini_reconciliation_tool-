<?php

namespace Database\Seeders;

use App\Models\InternalTransaction;
use App\Models\ProviderTransaction;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $internal = [
            [
                'reference' => 'TX101',
                'amount' => 500,
                'status' => 'completed'
            ],
            [
                'reference' => 'TX104',
                'amount' => 250,
                'status' => 'failed'
            ],
            [
                'reference' => 'TX103',
                'amount' => 700,
                'status' => 'completed'
            ],
            [
                'reference' => 'TX105',
                'amount' => 100,
                'status' => 'pending'
            ],
        ];

        $provider = [
            [
                'reference' => 'TX101',
                'amount' => 500,
                'status' => 'completed'
            ],
            [
                'reference' => 'TX102',
                'amount' => 250,
                'status' => 'success'
            ],
            [
                'reference' => 'TX104',
                'amount' => 700,
                'status' => 'completed'
            ],
            [
                'reference' => 'TX105',
                'amount' => 100,
                'status' => 'pending'
            ],
        ];

        foreach ($internal as $int) {
            InternalTransaction::create([
                'transaction_reference' => $int['reference'],
                'amount' => $int['amount'],
                'status' => $int['status'],
            ]);
        }

        foreach ($provider as $prov) {
            ProviderTransaction::create([
                'transaction_reference' => $prov['reference'],
                'amount' => $prov['amount'],
                'status' => $prov['status'],
            ]);
        }
    }
}
