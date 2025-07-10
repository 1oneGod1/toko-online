<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BankAccount;

class BankAccountSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $bankAccounts = [
            [
                'bank_name' => 'BCA',
                'account_number' => '1234567890',
                'account_holder_name' => 'PT Toko Online Indonesia',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'bank_name' => 'Mandiri',
                'account_number' => '1380012345678',
                'account_holder_name' => 'PT Toko Online Indonesia',
                'is_active' => true,
                'sort_order' => 2,
            ],
        ];

        foreach ($bankAccounts as $account) {
            BankAccount::create($account);
        }
    }
}