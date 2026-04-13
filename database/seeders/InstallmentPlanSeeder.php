<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InstallmentPlan;

class InstallmentPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            ['bank_name' => 'Ziraat Bankası',   'installment_count' => 2,  'interest_rate' => 0,   'sort_order' => 1],
            ['bank_name' => 'Ziraat Bankası',   'installment_count' => 3,  'interest_rate' => 0,   'sort_order' => 2],
            ['bank_name' => 'Ziraat Bankası',   'installment_count' => 6,  'interest_rate' => 1.5, 'sort_order' => 3],
            ['bank_name' => 'Ziraat Bankası',   'installment_count' => 9,  'interest_rate' => 2.5, 'sort_order' => 4],
            ['bank_name' => 'Ziraat Bankası',   'installment_count' => 12, 'interest_rate' => 3.5, 'sort_order' => 5],
            ['bank_name' => 'Garanti Bankası',  'installment_count' => 2,  'interest_rate' => 0,   'sort_order' => 6],
            ['bank_name' => 'Garanti Bankası',  'installment_count' => 3,  'interest_rate' => 0,   'sort_order' => 7],
            ['bank_name' => 'Garanti Bankası',  'installment_count' => 6,  'interest_rate' => 1.8, 'sort_order' => 8],
            ['bank_name' => 'Garanti Bankası',  'installment_count' => 12, 'interest_rate' => 3.8, 'sort_order' => 9],
            ['bank_name' => 'İş Bankası',       'installment_count' => 3,  'interest_rate' => 0,   'sort_order' => 10],
            ['bank_name' => 'İş Bankası',       'installment_count' => 6,  'interest_rate' => 1.6, 'sort_order' => 11],
            ['bank_name' => 'İş Bankası',       'installment_count' => 12, 'interest_rate' => 3.6, 'sort_order' => 12],
            ['bank_name' => 'Yapı Kredi',       'installment_count' => 3,  'interest_rate' => 0,   'sort_order' => 13],
            ['bank_name' => 'Yapı Kredi',       'installment_count' => 6,  'interest_rate' => 1.7, 'sort_order' => 14],
            ['bank_name' => 'Yapı Kredi',       'installment_count' => 12, 'interest_rate' => 3.7, 'sort_order' => 15],
        ];

        foreach ($plans as $plan) {
            InstallmentPlan::create($plan);
        }
    }
}
