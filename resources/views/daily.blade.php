@extends('layout')

@section('content')
        <p>Last Activity: {{$last_update}}</p>
        @foreach($members as $mid => $fullname)
            <h2>{{$fullname}}</h2>

            <h3>Cosa ho fatto ieri? Che problemi ho avuto?</h3>

            <h3>Cosa farò oggi?</h3>
            @if(empty($activity_today[$mid]))
                <p>Nessuna attività in TODAY</p>
            @else
                @foreach($activity_today[$mid] as $card)
                    <p>{{$card->name}} SCORE: {{$card->score}}</p>
                @endforeach
            @endif

            <h3>Cosa farò oggi? (Rejected)</h3>
            @if(empty($activity_today_rejected[$mid]))
                <p>Nessuna attività in REJECTED</p>
            @else
                @foreach($activity_today_rejected[$mid] as $card)
                    <p>{{$card->name}} SCORE: {{$card->score}}</p>
                @endforeach
            @endif

            <h3>Cosa farò oggi? (Almost there)</h3>
            @if(empty($activity_today_almost_there[$mid]))
                <p>Nessuna attività in ALMOST THERE</p>
            @else
                @foreach($activity_today_almost_there[$mid] as $card)
                    <p>{{$card->name}} SCORE: {{$card->score}}</p>
                @endforeach
            @endif

        @endforeach
@endsection
