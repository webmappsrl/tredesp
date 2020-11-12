<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use App\Models\Card;

class DashboardController extends Controller
{
    public function index()
    {
        $data = array();
        $data['last_update']=Card::max('updated_at');

        $data['tot_cards']=Card::query()->count('*');
        $data['done_cards']=Card::query()->where('list','=','DONE')->count('*');
        $data['done_cards_perc']=number_format($data['done_cards']/$data['tot_cards']*100,2);
        $data['todo_cards']=Card::query()->count('*')-Card::query()->where('list','=','DONE')->count('*');
        $data['todo_cards_perc']=number_format((1-$data['done_cards']/$data['tot_cards'])*100,2);

        $data['tot_score']=Card::sum('score');
        $data['done_score']=Card::query()->where('list','=','DONE')->sum('score');
        $data['done_score_perc']=number_format($data['done_score']/$data['tot_score']*100,2);
        $data['todo_score']=Card::sum('score')-Card::query()->where('list','=','DONE')->sum('score');
        $data['todo_score_perc']=number_format((1-$data['done_score']/$data['tot_score'])*100,2);

        // Build members array
        $data['members'] = array() ;
        foreach(Member::all() as $m) {
            if(count($m->cards)>0) {
                $data['members'][] =
                    array(
                        'username'=>$m->username,
                        'full_name'=>$m->full_name,

                        'tot_cards'=>count($m->cards),
                        'done_cards'=>$m->cards->where('list','DONE')->count('*'),
                        'done_cards_perc'=>number_format($m->cards->where('list','DONE')->count('*')/count($m->cards)*100,2),
                        'todo_cards'=>count($m->cards)-$m->cards->where('list','DONE')->count('*'),
                        'todo_cards_perc'=>number_format((1-$m->cards->where('list','DONE')->count('*')/count($m->cards))*100,2),

                        'tot_score'=>$m->cards->sum('score'),
                        'done_score'=>$m->cards->where('list','DONE')->sum('score'),
                        'done_score_perc'=>number_format($m->cards->where('list','DONE')->sum('score')/$m->cards->sum('score')*100,2),
                        'todo_score'=>$m->cards->sum('score')-$m->cards->where('list','DONE')->sum('score'),
                        'todo_score_perc'=>number_format((1-$m->cards->where('list','DONE')->sum('score')/$m->cards->sum('score'))*100,2)
                    );
            }
        }

        return view('dashboard',$data);
    }
    public function daily() {
        $data = array();
        $data['last_update']=Card::max('updated_at');
        $activity_yesterday=[];
        $activity_today=[];
        $activity_today_almost_there=[];
        $activity_today_rejected=[];
        foreach(Member::all() as $m) {
            if (count($m->cards)>0) {
                $data['members'][$m->id]=$m->full_name;
                // Activity yesterday
                // Activity today
                $activity_today[$m->id]=[];
                if($m->cards->where('list','TODAY')->count()>0) {
                    foreach($m->cards->where('list','TODAY') as $card) {
                        $activity_today[$m->id][]=$card;
                    }
                }
                // Activity today / rejected
                $activity_today_rejected[$m->id]=[];
                if($m->cards->where('list','REJECTED')->count()>0) {
                    foreach($m->cards->where('list','REJECTED') as $card) {
                        $activity_today_rejected[$m->id][]=$card;
                    }
                }
                // Activity today / almost there
                $activity_today_almost_there[$m->id]=[];
                if($m->cards->where('list','ALMOST THERE')->count()>0) {
                    foreach($m->cards->where('list','ALMOST THERE') as $card) {
                        $activity_today_almost_there[$m->id][]=$card;
                    }
                }
            }
        }
        $data['activity_today'] = $activity_today;
        $data['activity_today_rejected'] = $activity_today_rejected;
        $data['activity_today_almost_there'] = $activity_today_almost_there;
        return view('daily',$data);
    }


}
