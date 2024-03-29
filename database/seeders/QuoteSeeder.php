<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Quote;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuoteSeeder extends Seeder
{
    public static array $symbols = [
        'IBM',
        'AAPL',
        'MSFT',
        'AMZN',
        'TSLA',
        'AMD',
        'NVDA',
        'ADBE',
        'ACT',
        'ADD',
    ];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];

        foreach (static::$symbols as $symbol) {
            $data[] = [
                'symbol' => $symbol,
                'created_at' => now()->toDateTimeString(),
                'updated_at' => now()->toDateTimeString(),
            ];
        }

        DB::table('quotes')->insert($data);
    }
}
