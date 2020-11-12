<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tredesp</title>
    <style>
        table{
            border-collapse: collapse;
        }
        th, td {
            border: 2px solid black;
            border-collapse: collapse;
        }
        th, td {
            padding: 15px;
            text-align: center;
        }
        td.member {
            text-align: right;
        }
        tr.totale > td {
            font-style: italic;
            font-weight: bold;
        }
        th.first {
            border: 0px;
        }
        .perc {
            font-style: italic;
            font-size: x-small;
        }
    </style>
</head>
<body>
<div class="menu">
    <a href="/">Home</a>
    <a href="/daily">Daily</a>
</div>
<div class="header">
    <h1>TreDeSp - Trello Design and Sprint monitor</h1>
</div>

<div class="content">

    @yield('content')

</div>

</body>
</html>
