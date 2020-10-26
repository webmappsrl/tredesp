<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Unirest;
use App\Models\Card;
use App\Models\Member;

class CardSeeder extends Seeder
{
    private $bid = 'qxqVS51D';
    private $lists=array();
    private $list_done_id;
    private $cards=array();

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Download all cards from SPRINT BOARD
        $this->setLists();
        $this->command->info('DONE list has ID: '.$this->list_done_id);

        $this->setMembers();
        $this->command->info('Found '.Member::query()->count('*').' members');
        foreach (Member::all() as $m) {
            if (count($m->cards)>0) {
                $this->command->info("{$m->full_name}({$m->username}) has ".count($m->cards)." cards");
            }
        }

        $this->setCards();
        $this->command->info('Found '.count($this->cards).' cards on board SPRINT');

        $this->syncCards();
        $this->command->info('Found '.\App\Models\Card::query()->count().' cards in local DB');
    }

    private function unirest($q) {
        $key = "a61bcdeca7e386a82fcad570325dcd1f";
        $token = "00d9171f1e890243f1b257f049d3378bbbd158e61a842e44323d7fef9bd08aba";
        $headers = array('Accept' => 'application/json');
        $query = array('key' => $key, 'token' => $token);
        $r = Unirest\Request::get($q, $headers, $query);
        return $r->body;
    }

    private function setLists() {
        $q = 'https://api.trello.com/1/boards/'.$this->bid.'/lists';
        foreach ($this->unirest($q) as $list) {
            if($list->name=='DONE') {
                $this->list_done_id = $list->id;
            }
            $this->lists[$list->id]=$list->name;
        }
    }

    /**
     {#737
    +"id": "5090ddf935c6f90249000037"
    +"username": "alessiopiccioli"
    +"fullName": "Alessio Piccioli"
    }
     */
    private function setMembers() {
        $q = 'https://api.trello.com/1/boards/'.$this->bid.'/members';
        foreach ($this->unirest($q) as $m) {
            $member = Member::query()->where('trello_id','=',$m->id)->firstOrNew();
            $member->trello_id = $m->id;
            $member->username = $m->username;
            $member->full_name = $m->fullName;
            $member->save();
        }
    }

    /**
     * {#730
    +"id": "5f57a1c426bd238320617528"
    +"checkItemStates": null
    +"closed": false
    +"dateLastActivity": "2020-10-21T07:14:45.446Z"
    +"desc": ""
    +"descData": null
    +"dueReminder": null
    +"idBoard": "5dad99971513d786e12838af"
    +"idList": "5efd63bd256ae26d95b2bf4b"
    +"idMembersVoted": []
    +"idShort": 3118
    +"idAttachmentCover": null
    +"idLabels": []
    +"manualCoverAttachment": false
    +"name": "Pagina Route - Design/Comportamento pulsante preventivo nel caso Coming soon/ Scarica"
    +"pos": 6715411.1875
    +"shortLink": "S1tWBObc"
    +"isTemplate": false
    +"badges": {#733
    +"attachmentsByType": {#732
    +"trello": {#731
    +"board": 0
    +"card": 1
    }
    }
    +"location": false
    +"votes": 0
    +"viewingMemberVoted": false
    +"subscribed": false
    +"fogbugz": ""
    +"checkItems": 4
    +"checkItemsChecked": 0
    +"checkItemsEarliestDue": null
    +"comments": 0
    +"attachments": 1
    +"description": false
    +"due": null
    +"dueComplete": false
    +"start": null
    }
    +"dueComplete": false
    +"due": null
    +"idChecklists": array:2 [
    0 => "5f57a2212b8a956ba4055465"
    1 => "5f57a222c72be87cefcb3a40"
    ]
    +"idMembers": array:1 [
    0 => "5aaf75c39afc75446f6a671e"
    ]
    +"labels": []
    +"shortUrl": "https://trello.com/c/S1tWBObc"
    +"start": null
    +"subscribed": false
    +"url": "https://trello.com/c/S1tWBObc/3118-pagina-route-design-comportamento-pulsante-preventivo-nel-caso-coming-soon-scarica"
    +"cover": {#734
    +"idAttachment": null
    +"color": null
    +"idUploadedBackground": null
    +"size": "normal"
    +"brightness": "light"
    }
    }

     */
    private function setCards() {
        $q = 'https://api.trello.com/1/boards/'.$this->bid.'/cards';
        foreach ($this->unirest($q) as $card) {
            $this->cards[$card->id]['dateLastActivity']=strtotime($card->dateLastActivity);
            $this->cards[$card->id]['name']=$card->name;
            $this->cards[$card->id]['list']=$this->lists[$card->idList];
            $this->cards[$card->id]['member']=null;
            if(is_array($card->idMembers) && count($card->idMembers)>0) {
                $this->cards[$card->id]['member']=$card->idMembers[0];
            }
        }
    }

    private function syncCards() {
        foreach ($this->cards as $trello_id => $card_data) {
            $card = \App\Models\Card::query()->where('trello_id',$trello_id)->first();
            if (!$card) {
                $this->command->info('Creating card '.$trello_id);
                $data=array();
                $data['trello_id']=$trello_id;
                $data['name']=$card_data['name'];
                $card = new \App\Models\Card($data);
                $card->save();

                $this->syncCard($trello_id);
            }
        }

        // PRUNE AND UPDATE
        foreach (\App\Models\Card::all() as $card) {
            if (!array_key_exists($card->trello_id,$this->cards)) {
                $card->delete();
                $this->command->info('Deleting no more existing card '.$card->trello_id);
            } else {
                if($this->cards[$card->trello_id]['dateLastActivity'] > strtotime($card->updated_at)) {
                    $this->syncCard($card->trello_id);
                }
            }
        }

    }

    private function syncCard($id) {
        // TODO: implement sync single card
        $this->command->warn('TBI: card '.$id.' need to be updated');
        //dd($this->cards[$id]);
        $card=Card::where('trello_id','=',$id)->first();
        $card->name=$this->cards[$id]['name'];
        $card->list=$this->cards[$id]['list'];

        // Members with FK
        if(!is_null($this->cards[$id]['member'])) {
            $member = Member::query()->where('trello_id','=',$this->cards[$id]['member'])->first();
            $card->member_id=$member->id;
        }

        // Recupera SCORE da planning poker
        foreach($this->unirest('https://api.trello.com/1/cards/'.$id.'/pluginData') as $data) {
            if ($data->idPlugin == '597cbecff4fe5f1d91d4b614') {
                $value = json_decode($data->value);
                $card->score=$value->estimate;
            }
        }
        $card->save();
    }
}


