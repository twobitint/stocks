<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Imports\PositionsImport;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function update(Request $request)
    {
        if ($request->isMethod('POST')) {
            if ($date = $request->input('date')) {
                $importer = new PositionsImport($date);
            } else {
                $importer = new PositionsImport;
            }
            Excel::import($importer, $request->file('file'));
            return redirect()->route('home');
        }

        return view('update');
    }

    public function home()
    {
        $d1 = $this->tabulate(1);
        $d2 = $this->tabulate(2);

        $dates = array_keys($d1);
        $latestDate = $dates[count($dates) - 1];

        $chart = [
            'labels' => $dates,
            'series' => [
                array_reduce($d1, function ($carry, $date) {
                    array_push($carry, $date['total']);
                    return $carry;
                }, []),
                array_reduce($d2, function ($carry, $date) {
                    array_push($carry, $date['total']);
                    return $carry;
                }, []),
            ],
        ];

        return view('home', [
            'chart' => $chart,
            'positions1' => $d1[$latestDate]['positions'],
            'positions2' => $d2[$latestDate]['positions'],
            'cash1' => $d1[$latestDate]['cash'],
            'cash2' => $d2[$latestDate]['cash'],
            'total1' => $d1[$latestDate]['total'],
            'total2' => $d2[$latestDate]['total'],
            'change1' => $d1[$latestDate]['change'],
            'change2' => $d2[$latestDate]['change'],
        ]);
    }

    protected function tabulate($account)
    {
        $positions = Position::where('account', $account)
            ->orderBy('date')
            ->get();
        $d = [];
        foreach ($positions as $position) {
            if (!isset($d[$position->date])) {
                $d[$position->date] = [
                    'positions' => [],
                    'cash' => 0,
                    'change' => 0,
                    'total' => 0,
                ];
            }
            $use = &$d[$position->date];
            if (in_array($position->symbol, ['SPAXX**', 'Pending Activity'])) {
                $use['cash'] += $position->value;
                $use['total'] += $position->value;
            } else {
                $use['positions'][] = $position;
                $use['total'] += $position->value;
                $use['change'] += $position->today_gain_dollar;
            }
        }
        // Sort positions
        foreach (array_keys($d) as $date) {
            usort($d[$date]['positions'], function ($a, $b) {
                return $b->value - $a->value;
            });
        }

        return $d;
    }
}
