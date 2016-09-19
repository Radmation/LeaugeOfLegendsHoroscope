<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use GuzzleHttp\Client;

class ChampionController extends Controller
{
    //
    public function getAll()
    {
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
                'champData' => 'image'
            ]
        ]);

        if ($response->getStatusCode() == 200) {
            $body = $response->getBody();
            $object_body = json_decode($body); // make json object


//            var_dump($object_body->data);

            // iterate over each champion
            foreach ($object_body->data as $d) {
                echo $d->id . "<br>";
                echo $d->name . "<br>";
                echo $d->title . "<br>";
                echo $d->image->full . "<br>";
                echo "<img src='http://ddragon.leagueoflegends.com/cdn/6.18.1/img/champion/" .$d->image->full."'>";
                echo "<br><br>";
            }

            echo $object_body->data->Aatrox->id;
            echo "<br>";
            echo $object_body->data->Lux->id;
        }
//        var_dump($result);
//        dd($response);
//        echo $response->getBody()->read(4);

    }
}


