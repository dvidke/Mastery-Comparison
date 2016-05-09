 $(function(){

  $('[data-toggle="tooltip"]').tooltip();

  initVariables();
  centerize_content(mc);
  centerize_content(vs);

  $(window).resize(function() {
    initVariables();
    centerize_content(mc);
    centerize_content(vs);
  }) 

  $(document).click(function() {
    $('#main-container [data-toggle="tooltip"]').tooltip('hide');
  });

  $(document).on('click','#twitter-share',function() {
    if (tie) {
      var description = "It was a tie between "+$('#winner').text()+" and "+$('#looser').text()+" with "+$('select[name="champion"] option:selected').html()+". @ https://mastery-comparison.herokuapp.com";
    }else{
      var description = $('#winner').text()+" has more points with "+$('select[name="champion"] option:selected').html()+" than "+$('#looser').text()+"! @ https://mastery-comparison.herokuapp.com";
    }
    window.open('https://twitter.com/intent/tweet?text='+description);
  });
  $(document).on('click','#fb-share',function() {
    postToFeed($('#middle-content h1').text(),champ_url,$('#winner').text(),$('#looser').text(),$('select[name="champion"] option:selected').html());
  });

  $(document).on('click','#replay',function() {
    location.reload();
  });

  $(document).on('click','#random',function() {
   var champs = $('select[name="champion"]').find('option'),
   random = ~~(Math.random() * champs.length);
   champs.eq(random).prop('selected', true);
 });


  $(document).on('submit','#start-form',function(e){
    e.preventDefault();

    if ($('select[name="champion"]').val() == "17") {
      $('audio').get(0).play();
    }

    overlay.css('z-index','3').animate({ backgroundColor:'rgba(0,0,0,0.9)'},1000);

    l1.animate({ height: (wh/2)-50},1000);
    l2.animate({ height: (wh/2)-50},1000);
    vs.fadeIn(500);

    $.ajax({
      url: '/',
      type: 'POST',
      data: { 
        _token: $('input[name="_token"]').val(),
        champion: $('select[name="champion"]').val(),
        summ_1: $('input[name="summoner-1"]').val(),
        summ_2: $('input[name="summoner-2"]').val(),
        summ_1_region: $('select[name="summ1-region"]').val(),
        summ_2_region: $('select[name="summ2-region"]').val()
      },
      success: function(result){
        if (result == "summ1-not-exist") {
          l1.css('height',0);
          l2.css('height',0);
          vs.fadeOut(500);
          $('input[name="summoner-1"]').attr('data-original-title', "Summoner doesn't exist in the region.").tooltip('show');
          $('.tooltip-inner').css('background-color','#e8274b!important');
          $('.tooltip-arrow').css('border-top-color','#e8274b!important');
        } else if (result == "summ2-not-exist") {
          l1.css('height',0);
          l2.css('height',0);
          vs.fadeOut(500);
          $('input[name="summoner-2"]').attr('data-original-title', "Summoner doesn't exist in the region.").tooltip('show');
          $('.tooltip-inner').css('background-color','#e8274b!important');
          $('.tooltip-arrow').css('border-top-color','#e8274b!important');
        } else {
          $('#dutz, #overlay, #main-container').hide();
          result = $.parseJSON(result);
          summ_1 = result[0];
          summ_2 = result[1];
          champ_key = result[2];

          $('#summoner_1 .content, #summ_1').append('<img src="http://ddragon.leagueoflegends.com/cdn/6.9.1/img/profileicon/'+summ_1.profileIconId+'.png" class="img-responsive" /><h3>'+summ_1.name+'</h3><h4>'+summ_1.rank.tier+' '+summ_1.rank.division+'</h4>');
          $('#summoner_2 .content, #summ_2').append('<img src="http://ddragon.leagueoflegends.com/cdn/6.9.1/img/profileicon/'+summ_2.profileIconId+'.png" class="img-responsive" /><h3>'+summ_2.name+'</h3><h4>'+summ_2.rank.tier+' '+summ_2.rank.division+'</h4>');

          $('#summoner_1, #summoner_2').fadeIn(500);
          var margin = ($(window).outerHeight()-$('#summoner_1 .content').outerHeight())/2;
          $('#summoner_1 .content').css({'margin-top' : margin-1, 'margin-bottom' : margin-1});
          var margin = ($(window).outerHeight()-$('#summoner_2 .content').outerHeight())/2;
          $('#summoner_2 .content').css({'margin-top' : margin-1, 'margin-bottom' : margin-1});

          $("#arrow").hide();
          $('#versus').append('<p>Choose</p>').css('width' , 'auto');
          $('#versus P').fadeIn(500);
          $('#versus').css('left',($(window).outerWidth()-$('#versus').width())/2);
          champ_url = "http://ddragon.leagueoflegends.com/cdn/img/champion/splash/"+champ_key+"_0.jpg";

          $('#bg').css('background-image','url("'+champ_url+'")');

          if (summ_1.mastery[0] != null) {
            summ_1_points = summ_1.mastery[0].championPoints;
          } else {
            summ_1_points = 0;
          }

          if (summ_2.mastery[0] != null) {
            summ_2_points = summ_2.mastery[0].championPoints;
          } else {
            summ_2_points = 0;
          }

          if (summ_1_points > summ_2_points) {
            $('#summoner_1').data('winner','true');
            $('#winner').text(summ_1.name);
            $('#looser').text(summ_2.name);
          }else if (summ_1_points < summ_2_points){
            $('#summoner_2').data('winner','true');
            $('#winner').text(summ_2.name);
            $('#looser').text(summ_1.name);
          } else {
            $('#winner').text(summ_1.name);
            $('#looser').text(summ_2.name);
            tie = true;
          }
        }
        overlay.css('z-index','0').animate({ backgroundColor:'transparent'},1000);
      },
      error: function(result){
        console.log("error: "+result);
      }
    })
  });
 $(document).on('click','.summoner',function(){
   if ($(this).data('winner') == "true") {
    $('div[data-summ="'+$(this).attr('id')+'"] IMG').css({'-webkit-box-shadow' : '0 0 30px #00FF00', '-moz-box-shadow' : '0 0 30px #00FF00', 'box-shadow' : '0 0 30px #00FF00'});
  } else if (tie){
    $('div[data-summ="'+$(this).attr('id')+'"] IMG').css({'-webkit-box-shadow' : '0 0 30px #fff', '-moz-box-shadow' : '0 0 30px #fff', 'box-shadow' : '0 0 30px #fff'});
  } else {
    $('div[data-summ="'+$(this).attr('id')+'"] IMG').css({'-webkit-box-shadow' : '0 0 30px red', '-moz-box-shadow' : '0 0 30px red', 'box-shadow' : '0 0 30px red'});
  }

  var ctx = document.getElementById("chart");
  var chart = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: [summ_1.name, summ_2.name],
      datasets: [{
        label: '# of Points',
        data: [summ_1_points, summ_2_points],
        backgroundColor: [
        "#FF6384",
        "#36A2EB",
        ],
        hoverBackgroundColor: [
        "#FF6384",
        "#36A2EB",
        ]
      }]
    },
    options: {
      fullWidth : false,
      responsive : true,
    }
  });

  if ($(this).data('winner') == "true") {
    $('#middle-content').prepend('<h1>Yes, you guessed it!</h1>');
  } else if(tie){
    $('#middle-content').prepend('<h1>Tie.</h1>');
  } else {
    $('#middle-content').prepend('<h1>Unfortunately, you did not guessed it.</h1>');
  }

  if (summ_1.mastery[0] !== null) {
    $('#summ_1_lvl').append('<img src="/assets/img/lvl_'+summ_1.mastery[0].championLevel+'.png" class="img-responsive" data-toggle="tooltip" data-original-title="Mastery LVL : '+summ_1.mastery[0].championLevel+'" />');
    if (summ_1.mastery[0].chestGranted) {
      $('#summ_1_chest IMG').addClass('disabled').data('original-title','Chest is not obtainable');
    }
  } else{
    $('#summ_1_lvl').append('<img src="/assets/img/lvl_1.png" class="img-responsive" data-toggle="tooltip" data-original-title="Mastery LVL : 0" />');
    $('#summ_1_lvl').addClass('disabled');
  }

  if (summ_2.mastery[0] !== null) {
    $('#summ_2_lvl').append('<img src="/assets/img/lvl_'+summ_2.mastery[0].championLevel+'.png" class="img-responsive" data-toggle="tooltip" data-original-title="Mastery LVL : '+summ_2.mastery[0].championLevel+'"  />');
    if (summ_2.mastery[0].chestGranted) {
      $('#summ_2_chest IMG').addClass('disabled').data('original-title','Chest is not obtainable');
    }
  } else {
    $('#summ_2_lvl').append('<img src="/assets/img/lvl_1.png" class="img-responsive" data-toggle="tooltip" data-original-title="Mastery LVL : 0" />');
    $('#summ_2_lvl').addClass('disabled');
  }

  $('[data-toggle="tooltip"]').tooltip();

  $('#versus').fadeOut(500,function(){
    l1.animate({ height : 0},500);
    l2.animate({ height : 0},500,function(){
      $('.line, #summoners').fadeOut(500, function(){
        $('#bg, #result').fadeIn(500);

        centerize_content($('#summ_1'));
        centerize_content($('#summ_2'));
      })
    });
  })

  setTimeout(function(){ 
    var margin = ($(window).outerHeight()-$('#middle-content').outerHeight())/2;
    $('#middle-content').css({'margin-top' : margin-1, 'margin-bottom' : margin-1});
  }, 2500);

});

})

 function centerize_content(content){
  var margin = ($(window).outerHeight()-content.outerHeight())/2;
  content.css({'margin-top' : margin-1, 'margin-bottom' : margin-1});
}

function numberWithCommas(x) {
  return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function initVariables(){
  wh = $(window).outerHeight();
  ww = $(window).outerWidth();
  mc = $('#main-container');
  vs = $('#versus');
  l = $('.line');
  l1 = $('#line-1');
  l2 = $('#line-2');
  overlay = $('#overlay');
  tie = false;
}

function postToFeed(result,champion_image,winner,looser,champion,tie) {
  if (tie) {
    var description = "It was a tie between "+winner+" and "+looser+" with "+champion;
  }else{
    var description = winner+" has more points with "+champion+" than "+looser+"!"
  }
  var obj = {
    method: 'feed',
    link: 'https://mastery-comparison.herokuapp.com/',
    description: description,
    picture: champion_image,
    name: result      
  };
  FB.ui(obj);
}
