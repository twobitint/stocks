<?php

namespace App\Imports;

use App\Models\Position;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;

class PositionsImport implements ToModel, WithHeadingRow, WithUpserts
{
    protected $date;

    public function __construct($date = null)
    {
        $this->date = $date ?? now()->toDateString();
    }

    /**
     * @return string|array
     */
    public function uniqueBy()
    {
        return ['account', 'symbol', 'date'];
    }

    /**
     * @param array $row
     *
     * @return User|null
     */
    public function model(array $row)
    {
        $alias = $this->convertAccountToAlias($row['account_namenumber']);

        if (!$alias) {
            return null;
        }

        $position = new Position([
            'account' => $alias,
            'symbol' => $row['symbol'],
            'date' => $this->date,
        ]);

        if ($row['symbol'] == 'Pending Activity') {
            $position->type = 'Pending Activity';
            $position->value = $this->clean($row['last_price_change'] ?? $row['current_value']);
            return $position;
        }

        $position->description = $row['description'];
        $position->quantity = $this->clean($row['quantity']);
        $position->price = $this->clean($row['last_price']);
        $position->price_change = $this->clean($row['last_price_change']);
        $position->value = $this->clean($row['current_value']);

        if ($row['todays_gainloss_dollar'] != 'n/a') {
            $position->today_gain_dollar = $this->clean($row['todays_gainloss_dollar']);
            $position->today_gain_percent = $this->clean($row['todays_gainloss_percent']);
            $position->total_gain_dollar = $this->clean($row['total_gainloss_dollar']);
            $position->total_gain_percent = $this->clean($row['total_gainloss_percent']);
        }

        $position->percent_of_account = $this->clean($row['percent_of_account']);

        if ($row['cost_basis'] != 'n/a') {
            $position->cost_basis = $this->clean($row['cost_basis']);
            $position->cost_basis_per_share = $this->clean($row['cost_basis_per_share']);
        }

        $position->type = $row['type'];

        return $position;
    }

    /**
     * Mask the account incoming account name.
     */
    protected function convertAccountToAlias($account)
    {
        if ($account === config('services.account1')) {
            return 1;
        } else if ($account === config('services.account2')) {
            return 2;
        }
        return null;
    }

    /**
     * Remove special characters.
     */
    protected function clean($input)
    {
        return (float)str_replace(['%', '$', '+', ','], '', $input);
    }
}
