@extends('layout')

@section('content')
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
@endsection
