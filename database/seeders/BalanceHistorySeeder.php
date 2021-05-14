<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BalanceHistory;
use Carbon\Carbon;

class BalanceHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_id_max = 10;
        $value_max = 1000;
        $gap_min = 10;
        $gap_max = 3600;
        $transactions_count = 100;

        $gaps_list = [];
        for ($i = 1; $i <= $transactions_count; $i++) {
            $gaps_list[] = rand($gap_min, $gap_max);
        }

        BalanceHistory::flushEventListeners();
        BalanceHistory::factory($transactions_count)
            ->make()
            ->each(function ($row) use ($user_id_max, $value_max, &$gaps_list)
            {
                $user_id = rand(1, $user_id_max);
                $balance = BalanceHistory::whereUserId($user_id)
                    ->latest()
                    ->pluck('balance')
                    ->first();

                $value = rand(-($balance * 100), ($value_max * 100)) / 100;
                $balance += $value;

                $created_at = BalanceHistory::latest()
                    ->pluck('created_at')
                    ->first();

                if ($created_at) {
                    $datetime = Carbon::create($created_at)
                        ->addSeconds(array_shift($gaps_list))
                        ->toDateTimeString();
                } else {
                    $datetime = Carbon::now()
                        ->subSeconds(array_sum($gaps_list))
                        ->toDateTimeString();
                }

                $row->value = $value;
                $row->balance = $balance;
                $row->user_id = $user_id;
                $row->created_at = $datetime;
                $row->save();
            });
    }
}
