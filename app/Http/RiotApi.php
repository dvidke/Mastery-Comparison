<?php 

namespace App\Http;
use DB;

class RiotApi{

    /**
     * Place fresh champion list to the database from Riot API
     * @param  string $region
     * @return array
     */
    public function getChampionsData($region)
    {   
        $result = $this->curl('https://global.api.pvp.net/api/lol/static-data/' . $region . '/v1.2/champion?champData=all&api_key=' . env('RIOT_API_KEY'));
        $insert = [];
        foreach ($result[0]['data'] as $key => $value) {
            $insert[] = ['name' => $value['name'], 'champion_id' => $value['id'], 'title' => $value['title'], 'key' => $value['key']];
        }
        DB::table('champions')->insert($insert);
    }

    /**
     * Get Summoner infos by Summoner name from Riot API
     * @param  string $summoner_name Summoner name
     * @param  string $region        The region of the summoner
     * @return array                
     */
    public function getSummonerByName($summoner_name,$region){
        return $this->curl('https://'.$region.'.api.pvp.net/api/lol/'.$region.'/v1.4/summoner/by-name/'.rawurlencode($summoner_name).'?api_key=' . env('RIOT_API_KEY'));
    }

    public function getSummonerRank($summoner_id,$region){
        return $this->curl('https://'.$region.'.api.pvp.net/api/lol/'.$region.'/v2.5/league/by-summoner/'.$summoner_id.'/entry?api_key=' . env('RIOT_API_KEY'));
    }

    /**
     * Get Champion Mastery point
     * @param  string $summoner_id Summoner ID
     * @param  string $champion_id Champion ID
     * @param  string $region      Region
     * @return array
     */
    public function getChampionMasteryPoints($summoner_id,$champion_id,$region){
        return $this->curl('https://'.$region.'.api.pvp.net/championmastery/location/'.$this->regionTranslator($region).'/player/'.$summoner_id.'/champion/'.$champion_id.'?api_key=' . env('RIOT_API_KEY'));
    }


    /**
     * Translate region code specified to the ChampionMastery
     * @param  string $region   Region code
     * @return string
     */
    public function regionTranslator($region){
        switch ($region) {
            case 'br':
            $region = "br1";
            break;
            case 'eune':
            $region = "eun1";
            break;
            case 'euw':
            $region = "euw1";
            break;
            case 'jp':
            $region = "jp1";
            break;
            case 'lan':
            $region = "la1";
            break;
            case 'las':
            $region = "la2";
            break;
            case 'na':
            $region = "na1";
            break;
            case 'oc':
            $region = "oc1";
            break;
            case 'tr':
            $region = "tr1";
            break;
        }
        return $region;
    }

    /**
     * CURL
     * @param  string $url
     * @return array
     */
    public function curl($url)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLINFO_HEADER_OUT, 1);
        $result = curl_exec($curl);
        $response_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        switch ($response_code) {
            case '400':
                $response_meaning = 'Bad request 400';
            break;
            case '401':
                $response_meaning = 'Unauthorized 401';
            break;
            case '404':
                $response_meaning = 'Not Found 404';
            break;
            case '429':
                $response_meaning = 'Rate limit exceeded 429';
            break;
            case '500':
                $response_meaning = 'Internal server error 500';
            break;
            case '503':
                $response_meaning = 'Service unavailable 503';
            break;
            case '204':
                $response_meaning = 'The request was successful, but it wasnt returned with data';
            break;
            default:
                $response_meaning = true;
            break;
        }
        curl_close($curl);
        return [json_decode($result, true),$response_meaning];
    }
}