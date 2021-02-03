<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Stocks</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        <link rel="stylesheet" href="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
        <style>
            body {
                font-family: 'Nunito';
            }
            .ct-series-a .ct-point,
            .ct-series-a .ct-line,
            .c1 {
                stroke: #05396b;
                color: #05396b;
            }
            .ct-series-b .ct-point,
            .ct-series-b .ct-line,
            .c2 {
                stroke: #a3322a;
                color: #a3322a;
            }
        </style>

    </head>
    <body>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="ct-chart"></div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-6">
                    <h3 class="text-end fw-bold c1">Whumps</h3>
                    <ul class="px-0">
                        @foreach ($positions1 as $position)
                            @include('position', ['position' => $position, 'percent' => $position->value / $total1])
                        @endforeach
                        @include('position', ['position' => new App\Models\Position([
                            'symbol' => 'CASH', 'description' => 'CASH ON HAND', 'value' => $cash1
                        ]), 'percent' => $cash1 / $total1])
                        @include('position', ['position' => new App\Models\Position([
                            'value' => $total1,
                            'today_gain_dollar' => $change1,
                            'today_gain_percent' => number_format(round(abs($change1 / ($total1 - $change1) * 100), 2), 2)
                        ])])
                    </ul>
                </div>
                <div class="col-6">
                    <h3 class="fw-bold c2">Grumps</h3>
                    <ul class="px-0">
                        @foreach ($positions2 as $position)
                            @include('position', ['position' => $position])
                        @endforeach
                        @include('position', ['position' => new App\Models\Position(['symbol' => 'CASH', 'description' => 'CASH ON HAND', 'value' => $cash2])])
                        @include('position', ['position' => new App\Models\Position([
                            'value' => $total2,
                            'today_gain_dollar' => $change2,
                            'today_gain_percent' => number_format(round(abs($change2 / ($total2 - $change2) * 100), 2), 2)
                        ])])
                    </ul>
                </div>
            </div>
        </div>

        <!-- Scripts -->
        <script src="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>
        <script src="{{ mix('/js/app.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
        <script>
            new Chartist.Line('.ct-chart',
                @json($chart), {
                fullWidth: true,
                onlyInteger: true,
            });
        </script>
    </body>
</html>
