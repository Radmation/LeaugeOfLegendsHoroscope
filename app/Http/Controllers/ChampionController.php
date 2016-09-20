<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use GuzzleHttp\Client;

class ChampionController extends Controller
{
    //
    public function getAll( Request $request )
    {
        $fighter = [];
        $tank = [];
        $support = [];
        $assassin = [];
        $mage = [];
        $marksman = [];

        $all_champs = [];

        $team_comp = [];

//        return "All Champs Here Boi";

        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://na.api.pvp.net/api/lol/static-data/na/v1.2/',
            // You can set any number of default request options.
            'timeout' => 2.0,
        ]);;

        $response = $client->request('GET', 'champion', [
            'query' => ['api_key' => 'ac368055-6405-45ce-b423-70f037c6a78d']
        ]);

        $response = $client->request('GET', 'champion', [
            'query' => [
                'api_key' => 'ac368055-6405-45ce-b423-70f037c6a78d',
                'champData' => 'image,tags',
//                'champData' => ['tags', 'image']

            ]
        ]);


        if ($response->getStatusCode() == 200) {
            $body = $response->getBody();
            $object_body = json_decode($body); // make json object


//            var_dump($object_body->data);

            // iterate over each champion
            foreach ($object_body->data as $d) {
//                echo $d->id . "<br>";
//                echo $d->name . "<br>";
//                echo $d->title . "<br>";
//                echo $d->image->full . "<br>";
//                echo "Roles:" . "<br>";
//
//                foreach($d->tags as $key=>$tag) {
//                    echo "Option " . ($key + 1)  . " " . $tag . "<br>";
//                }

                array_push($all_champs, $d); // all champs in array

//                echo "<img src='http://ddragon.leagueoflegends.com/cdn/6.18.1/img/champion/".$d->image->full."'>";
//                echo "<br><br>";

                foreach($d->tags as $tag) {
                    switch ($tag){
                        case "Fighter":
                            array_push($fighter, $d);
                            break;
                        case "Tank":
                            array_push($tank, $d);
                            break;
                        case "Mage":
                            array_push($mage, $d);
                            break;
                        case "Assassin":
                            array_push($assassin, $d);
                            break;
                        case "Support":
                            array_push($support, $d);
                            break;
                        case "Marksman":
                            array_push($marksman, $d);
                            break;
                    }
                } // end foreach tag - populate arrays

            } // end foreach $object_body->data as $d
        } // end if status code 200

        $count = count($all_champs);
//        echo $count;


        $found_team = false;
        $have_marksman = false;
        $have_support = false;
        $have_jungle = false;
        $have_top = false;
        $have_mid = false;

        $team_comp = [];
        while($found_team == false) {
             // new random number
            // get the champ at the random index
            $current_champ = $all_champs[rand ( 0 , $count - 1 )];

            // make sure current team doesn't already have that champion
            if( in_array($current_champ, $team_comp) ){
                continue; // skip to next section
            }

//            echo $current_champ->name;


            foreach($current_champ->tags as $tag) {


                // TOP Tags
                if($tag == "Fighter" || $tag == "Tank") {
                    if($have_top == false ) {
                        if( in_array($current_champ, $team_comp) ){
                            continue; // skip to next section
                        }
                        array_push($team_comp, $current_champ);
                        $have_top = true;
                        continue;
                    }
                }
                // ADC Tags
                if($tag == "Marksman") {
                    if($have_marksman == false ) {
                        if( in_array($current_champ, $team_comp) ){
                            continue; // skip to next section
                        }
                        array_push($team_comp, $current_champ);
                        $have_marksman = true;
                        continue;
                    }
                }
                // MID Tags
                if($tag == "Mage" || $tag == "Assassin") {
                    if($have_mid == false) {
                        if( in_array($current_champ, $team_comp) ){
                            continue; // skip to next section
                        }
                        array_push($team_comp, $current_champ);
                        $have_mid = true;
                        continue;
                    }
                }
                // JUNGLE Tags
                if($tag == "Fighter" || $tag == "Assassin" || $tag == "Tank") {
                    if($have_jungle == false ) {
                        if( in_array($current_champ, $team_comp) ){
                            continue; // skip to next section
                        }
                        array_push($team_comp, $current_champ);
                        $have_jungle = true;
                        continue;
                    }
                }
                // SUPPORT Tags
                if($tag == "Support" ) {
                    if($have_support == false ) {
                        if( in_array($current_champ, $team_comp) ){
                            continue; // skip to next section
                        }
                        array_push($team_comp, $current_champ);
                        $have_support = true;
                        continue;
                    }
                }
            }


            if($have_marksman && $have_support && $have_jungle && $have_top && $have_mid) {
                $found_team = true; // breaks the while
                echo "OMG I AM DONE";
            }

        }
        var_dump($team_comp);

    } // end function getAll()

    public function showControls() {
        return view('page.roll');
    } // end showControls()


}


