<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\RiotApi;
use App\Champion;

class HomeController extends Controller
{
  public function index(){
    $champions = Champion::orderBy('name')->lists('name','champion_id');
    $riot_api = new RiotApi();
    return view('home', compact('champions'));
  }

  public function magic(Request $request){
    $riot_api = new RiotApi();
          $summ_1 = $riot_api->getSummonerByName($request->summ_1,$request->summ_1_region);

          if (!is_bool($summ_1[1])) {
            return 'summ1-not-exist'; // Summoner 1 is not exist in the region.
          }
          $bool_1 = $summ_1[1];
          $summ_1 = end($summ_1[0]);
          $summ_1['bool'] = $bool_1;

          $summ_2 = $riot_api->getSummonerByName($request->summ_2,$request->summ_2_region);
          if (!is_bool($summ_2[1])) {
            return 'summ2-not-exist'; // Summoner 2 is not exist in the region.
          }
          $bool_2 = $summ_2[1];
          $summ_2 = end($summ_2[0]);
          $summ_2['bool'] = $bool_2;

          $summ_1['mastery'] = $riot_api->getChampionMasteryPoints($summ_1['id'],$request->champion,$request->summ_1_region);
          $rank_1 = $riot_api->getSummonerRank($summ_1['id'],$request->summ_1_region);
          if (!is_bool($rank_1[1])) {
            $summ_1['rank']['tier'] = 'UNRANKED';
            $summ_1['rank']['division'] = '';
          } else {
            $summ_1['rank']['tier'] = end($rank_1[0])[0]['tier'];
            $summ_1['rank']['division'] = end($rank_1[0])[0]['entries'][0]['division'];
          }

          $summ_2['mastery'] = $riot_api->getChampionMasteryPoints($summ_2['id'],$request->champion,$request->summ_2_region);
          $rank_2 = $riot_api->getSummonerRank($summ_2['id'],$request->summ_2_region);
          if (!is_bool($rank_2[1])) {
            $summ_2['rank']['tier'] = 'UNRANKED';
            $summ_2['rank']['division'] = '';
          } else {
            $summ_2['rank']['tier'] = end($rank_2[0])[0]['tier'];
            $summ_2['rank']['division'] = end($rank_2[0])[0]['entries'][0]['division'];
          }

          $champion_key = Champion::where('champion_id',$request->champion)->value('key');
          return json_encode([$summ_1,$summ_2,$champion_key],1);
        }
      }
