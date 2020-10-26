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
    <div class="header">
        <h1>TreDeSp</h1>
        <h2>Trello Design and Sprint Monitor</h2>
    </div>

    <div class="content">
        <p>Last Activity: {{$last_update}}</p>

        <table>
            <thead>
            <tr>
                <th rowspan="2" class="first">&nbsp;</th>
                <th colspan="3">Score</th>
                <th colspan="3">Cards</th>
            </tr>
            <tr>
                <th>Todo</th>
                <th>Done</th>
                <th>Tot</th>
                <th>Todo</th>
                <th>Done</th>
                <th>Tot</th>
            </tr>
            </thead>
            <tbody>
            @foreach($members as $member)
                <tr>
                    <td class="member">{{$member['full_name']}}<br/>({{$member['username']}})</td>
                    <td>{{$member['todo_score']}} <br/> <span class="perc">{{$member['todo_score_perc']}} %</span></td>
                    <td>{{$member['done_score']}} <br/> <span class="perc">{{$member['done_score_perc']}} %</span></td>
                    <td>{{$member['tot_score']}} <br/> <span class="perc">100 %</span></td>
                    <td>{{$member['todo_cards']}} <br/> <span class="perc">{{$member['todo_cards_perc']}} %</span></td>
                    <td>{{$member['done_cards']}} <br/> <span class="perc">{{$member['done_cards_perc']}} %</span></td>
                    <td>{{$member['tot_cards']}} <br/> <span class="perc">100 %</span></td>

                </tr>
            @endforeach
            <tr class="totale">
                <td>Totale</td>
                <td>{{$todo_score}} <br/> <span class="perc">{{$todo_score_perc}} %</span></td>
                <td>{{$done_score}} <br/> <span class="perc">{{$done_score_perc}} %</span></td>
                <td>{{$tot_score}} <br/> <span class="perc">100 %</span></td>
                <td>{{$todo_cards}} <br/> <span class="perc">{{$todo_cards_perc}} %</span></td>
                <td>{{$done_cards}} <br/> <span class="perc">{{$done_cards_perc}} %</span></td>
                <td>{{$tot_cards}} <br/> <span class="perc">100 %</span></td>
            </tr>
            </tbody>

        </table>
    </div>

    </body>
</html>
