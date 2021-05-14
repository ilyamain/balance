<?php

declare(strict_types=1);

namespace App\Http\Procedures;

use Illuminate\Http\Request;
use Sajya\Server\Procedure;
use Sajya\Server\Exceptions\InvalidParams;

class BalanceProcedure extends Procedure
{
    const HISTORY_LIMIT = 50;
    /**
     * The name of the procedure that will be
     * displayed and taken into account in the search
     *
     * @var string
     */
    public static string $name = 'balance';

    /**
     * Execute the procedure.
     *
     * @param Request $request
     *
     * @return array|string|integer
     */
    public function handle(Request $request)
    {
        // write your code
    }

    /**
     * Return user balnce.
     *
     * @param Request $request
     *
     * @return array|string|integer
     */
    public function userBalance(Request $request)
    {
        if (!$request->has('user_id')) {
            return new InvalidParams;
        }

        return \App\Models\BalanceHistory::whereUserId($request->input('user_id'))
            ->latest()
            ->first();
    }

    /**
     * Return latest transactions.
     *
     * @param Request $request
     *
     * @return array|string|integer
     */
    public function history(Request $request)
    {
        $limit = ($request->has('limit')) ? $request->input('limit') : self::HISTORY_LIMIT;
        if ($limit > self::HISTORY_LIMIT) {
            $limit = self::HISTORY_LIMIT;
        }

        return \App\Models\BalanceHistory::latest()
            ->limit($limit)
            ->get();
    }
}
