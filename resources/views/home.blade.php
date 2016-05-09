<!DOCTYPE html>
<html>
<head>
  <title>Mastery Comparison</title>
  <link href='https://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" type="text/css" href="/assets/css/all.css">
</head>
<body>
 <!-- FB SCRIPT -->
 <script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '1620024358321526',
      xfbml      : true,
      version    : 'v2.6'
    });
  };

  (function(d, s, id){
   var js, fjs = d.getElementsByTagName(s)[0];
   if (d.getElementById(id)) {return;}
   js = d.createElement(s); js.id = id;
   js.src = "//connect.facebook.net/en_US/sdk.js";
   fjs.parentNode.insertBefore(js, fjs);
 }(document, 'script', 'facebook-jssdk'));
</script> 
<!-- END - FB SCRIPT -->

<div id="dutz"></div>
<div id="overlay"></div>
<div id="winner" class="hidden-xs-up"></div>
<div id="looser" class="hidden-xs-up"></div>

<audio class="hidden">
  <source src="/assets/voice/satan.ogg" type="audio/ogg">
    Your browser does not support the audio element.
  </audio>
  <div class="container" id="main-container">
    <a href="https://github.com/dvidke/Mastery-Comparison" target="_blank" id="github" class="btn btn-default">About on Github</a>
    <header class="text-xs-center">
      <h1>
        Compare Your Mastery!
      </h1>
      <h2>
        Guess who's the better with the specified Champion!
      </h2>
    </header>
    <form action="" method="post" role="form" id="start-form">
      <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
      <div class="row">
        <div class="col-sm-12 col-md-4">
          <input type="text" name="summoner-1" placeholder="Summoner 1" class="form-control" value="" required="required" data-toggle="tooltip" data-title="">
        </div>
        <div class="col-sm-12 col-md-4 col-md-offset-4">
          <input type="text" name="summoner-2" placeholder="Summoner 2" class="form-control" value="" required="required" data-toggle="tooltip" data-title="">
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12 col-md-4">
          <select class="form-control" name="summ1-region">
            <option value="br">BR</option>
            <option value="eune" selected>EUNE</option>
            <option value="euw">EUW</option>
            <option value="jp">JP</option>
            <option value="kr">KR</option>
            <option value="lan">LAN</option>
            <option value="las">LAS</option>
            <option value="na">NA</option>
            <option value="oce">OCE</option>
            <option value="ru">RU</option>
            <option value="tr">TR</option>
          </select>
        </div>
        <div class="col-sm-12 col-md-4 col-md-offset-4">
          <select class="form-control" name="summ2-region">
            <option value="br">BR</option>
            <option value="eune" selected>EUNE</option>
            <option value="euw">EUW</option>
            <option value="jp">JP</option>
            <option value="kr">KR</option>
            <option value="lan">LAN</option>
            <option value="las">LAS</option>
            <option value="na">NA</option>
            <option value="oce">OCE</option>
            <option value="ru">RU</option>
            <option value="tr">TR</option>
          </select>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12 col-md-4 col-md-offset-4">
          <select class="form-control" name="champion">
            @foreach ($champions as $champion_id => $name)
            <option value="{{ $champion_id }}">{{ $name }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12">
          <div class="row">
            <div class="hidden-sm-down col-md-6">
              <button type="submit" class="btn btn-success text-xs-center pull-xs-right" id="start">Start</button>
            </div>
            <div class="hidden-sm-down col-md-6">
              <button type="button" class="btn btn-primary text-xs-center pull-xs-left" id="random">Random</button>
            </div>
            <div class="hidden-md-up col-sm-12">
              <button type="submit" class="btn btn-success text-xs-center pull-sm-left" id="start">Start</button>
              <button type="button" class="btn btn-primary text-xs-center pull-sm-right" id="random">Random</button>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12 text-xs-center">
          <em>Fill the names of the summoners, the region where they are playing and the champion what you want to compare. For a random champion, press the Random button.</em>
        </div>
      </div>
      <p id="copyright" >Mastery Comparison isn’t endorsed by Riot Games and doesn’t reflect the views or opinions of Riot Games or anyone officially involved in producing or managing League of Legends. League of Legends and Riot Games are trademarks or registered trademarks of Riot Games, Inc. League of Legends © Riot Games, Inc.</p>
    </div>
  </form>
</div>

<div id="bg">
  <div id="bg-layer"></div>
</div>
<div class="container-fluid" id="result">
 <div class="row">
   <div class="col-xs-4 text-xs-center" id="summ_1" data-summ="summoner_1"></div>
   <div class="col-xs-4 text-xs-center fadeInDown animated" id="middle-content">
     <canvas id="chart" width="300" height="300"></canvas>
     <ul class="list-unstyled">
      <li class="row">
        <span class="col-xs-4" id="summ_1_lvl"></span>
        <strong class="col-xs-4">Mastery LVL</strong>
        <span class="col-xs-4" id="summ_2_lvl"></span>
      </li>
      <li class="row">
        <span class="col-xs-4" id="summ_1_chest"><img class="chest" src="/assets/img/chest.png" data-toggle="tooltip" data-original-title="Chest is obtainable"/></span>
        <strong class="col-xs-4">Chest</strong>
        <span class="col-xs-4" id="summ_2_chest"><img class="chest" src="/assets/img/chest.png" data-toggle="tooltip" data-original-title="Chest is obtainable"/></span>
      </li>
    </ul>
    <div class="row">
      <div class="col-xs-6">
        <button class="btn" id="fb-share">Facebook</button>
      </div>
      <div class="col-xs-6">
        <button class="btn" id="twitter-share">Twitter</button>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12">
        <button class="btn btn-warning" id="replay">Replay</button>
      </div>
    </div>
  </div>
  <div class="col-xs-4 text-xs-center" id="summ_2" data-summ="summoner_2"></div>
</div>
</div>

<div class="line" id="line-1"></div>
<div class="line" id="line-2"></div>
<div id="versus">
 <img id="arrow" src="/assets/img/arrow.png">
</div>
<div id="summoners">
  <div class="summoner" id="summoner_1" data-winner="">
    <div class="content text-xs-center"></div>
  </div>
  <div class="summoner" id="summoner_2" data-winner="">
    <div class="content text-xs-center"></div>
  </div>
</div> 

<script src="/assets/js/all.js" type="text/javascript"></script>

</body>
</html>
