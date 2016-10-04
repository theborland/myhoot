function loadWaitingForUsers(ip,gameID){
    var conn = new ab.Session('ws://'+ip+':8080',
    function() {
    //  alert('Game'+gameID);
        conn.subscribe('Game'+gameID, function(topic, data) {
            console.log('Waiting for users:"' + topic + '" : ' + data.title);
          var container = document.getElementById("nameUsers");
    container.innerHTML =	'<div class="sqName" id="user_w_name_' + data.title + '" onClick="removeUser(\''  +data.title+'\')" style="background:#'+data.color +';">'+data.title+'<div class="sqNameRemove"><img src="img/X.png"></div></div>'   + ""+container.innerHTML;
            var numUsers = document.getElementById("numUsers");
    numUsers.innerHTML = parseInt(numUsers.innerHTML)  + 1;
        });
    },
    function() {
        console.warn('WebSocket connection closed');
    },
    {'skipSubprotocolCheck': true}
    );



}
function loadWaitingForAnswers(ip,gameID,questionNumber,auto,numUsers){
    var conn = new ab.Session('ws://'+ip+':8080',
    function() {
        conn.subscribe('Game'+gameID+''+questionNumber+'', function(topic, data) {
            console.log('Getting name:"' + topic + '" : ' + data.title);
            console.log('Getting color:"' + topic + '" : ' + data.color);
            var numAnswers = document.getElementById("numAnswers");
            numAnswers.innerHTML = parseInt(numAnswers.innerHTML)  + 1;
            var userAnswers = document.getElementById("userAnswers");
            var r=hexToRgb(data.color).r;
            var g=hexToRgb(data.color).g;
            var b=hexToRgb(data.color).b;
            answersWrap.innerHTML = answersWrap.innerHTML  + '<div class="userAnswer" style="background-color: rgba('+r+','+g+','+b+',.8);">'+data.title+'<div class="userResult">'+data.miles+'</div></div>';

//<div class="userAnswer" style="background:#38D38E;">John <div class="userResult">434,134</div></div>
             if (parseInt(numAnswers.innerHTML) ==parseInt(numPlayers.innerHTML) && counter<27 && auto=="yes" && parseInt(numPlayers.innerHTML)>=numUsers)
              setTimeout(function (){
                    window.location.href='showAnswer.php';
                  }, 2000);

         });
    },
    function() {
        console.warn('WebSocket connection closed');
    },
    {'skipSubprotocolCheck': true}
    );
  }
  function findingNumberOfUsers(ip,gameID,questionNumber){
      var conn = new ab.Session('ws://'+ip+':8080',
      function() {
          conn.subscribe('InGame'+gameID+'-'+questionNumber, function(topic, data) {
              console.log('Getting answers:"' + topic + '" : ' + data.title);
              var numPlayers = document.getElementById("numPlayers");
              numPlayers.innerHTML = parseInt(numPlayers.innerHTML)  + 1;
          });
      },
      function() {
          console.warn('WebSocket connection closed');
      },
      {'skipSubprotocolCheck': true}
      );
    }
  function loadWaitingForQuestion(ip,gameID){
    var conn = new ab.Session('ws://'+ip+':8080',
    function() {
      conn.subscribe('Game'+gameID+'Status', function(topic, data) {
        console.log('Waiting for users:"' + topic + '" : ' + data.title + " : " + data.type);
        var container = document.getElementById("waitingDiv");
        if (data.type=="NextGame")
          window.location.href='joinQuiz.php?replay=yes&game_id='+data.title;
        else if (data.type=="end")
          window.location.href='waitingScreenEnd.php';
        else if (data.title.substring(0,1)=="R")
          window.location.href='joinQuiz.php?error=Reject';
        else if (data.title.substring(1)=="-1" && window.location.href.indexOf("waiting")==-1)
          window.location.href='waitingScreen.php?message=nosubmit';
        else if (data.title.substring(0,1)=="Q" && data.title!="Q-1")
        {
          if (data.type=="geo" || data.type=="pt" || data.type=="places")
              window.location.href='userScreen.php?question='+data.title.substring(1);
          else if (data.type=="facts")
                  window.location.href='userScreenDecimal.php?perc=no&question='+data.title.substring(1);
          else if (data.type=="factsMax")
                  window.location.href='userScreenDecimal.php?perc=no&max=yes&question='+data.title.substring(1);
          else if (data.type=="factsPercent")
                  window.location.href='userScreenDecimal.php?perc=yes&question='+data.title.substring(1);
          else if (data.type=="science" || data.type=="sports" || data.type=="entertainment" || data.type=="factsRand")
                  window.location.href='userScreenRand.php?question='+data.title.substring(1);
          else if (data.type.substring(0,9)=="WorldTime")
                  window.location.href='userScreenWorldTime.php?region='+data.type.substring(9)+'&perc=no&question='+data.title.substring(1);
          else
              window.location.href='userScreen'+capitalizeFirstLetter(data.type)+'.php?question='+data.title.substring(1);

        }
            });

    },
    function() {
      console.warn('WebSocket connection closed');
    },
    {'skipSubprotocolCheck': true}
  );
}

function loadWaitingForQuestionSingle(ip,gameID){
  var conn = new ab.Session('ws://'+ip+':8081',
  function() {
    conn.subscribe('Game'+gameID+'Status', function(topic, data) {
      console.log('Waiting for users:"' + topic + '" : ' + data.title + " : " + data.type);
      var container = document.getElementById("waitingDiv");
      if (data.type=="NextGame")
        window.location.href='joinQuiz.php?replay=yes&game_id='+data.title;
      else if (data.type=="end")
        window.location.href='waitingScreenEnd.php';
      else if (data.title.substring(0,1)=="R")
        window.location.href='joinQuiz.php?error=Reject';
      else if (data.title.substring(1)=="-1" && window.location.href.indexOf("waiting")==-1)
        window.location.href='waitingScreen.php?message=nosubmit';
      else if (data.title.substring(0,1)=="Q" && data.title!="Q-1")
      {
        if (data.type=="geo" || data.type=="pt" || data.type=="places")
            window.location.href='userScreen.php?question='+data.title.substring(1);
        else if (data.type=="facts")
                window.location.href='userScreenDecimal.php?perc=no&question='+data.title.substring(1);
        else if (data.type=="factsMax")
                window.location.href='userScreenDecimal.php?perc=no&max=yes&question='+data.title.substring(1);
        else if (data.type=="factsPercent")
                window.location.href='userScreenDecimal.php?perc=yes&question='+data.title.substring(1);
        else if (data.type=="science" || data.type=="sports" || data.type=="entertainment" || data.type=="factsRand")
                window.location.href='userScreenRand.php?question='+data.title.substring(1);
        else if (data.type.substring(0,9)=="WorldTime")
                window.location.href='userScreenWorldTime.php?region='+data.type.substring(9)+'&perc=no&question='+data.title.substring(1);
        else
            window.location.href='userScreen'+capitalizeFirstLetter(data.type)+'.php?question='+data.title.substring(1);

      }
          });

  },
  function() {
    console.warn('WebSocket connection closed');
  },
  {'skipSubprotocolCheck': true}
);
}

function loadWaitingForNextGame(ip,gameID){
  var conn = new ab.Session('ws://'+ip+':8080',
  function() {
    conn.subscribe('Game'+gameID+'NextGame', function(topic, data) {
      console.log('Waiting for nextgame:"' + topic + '" : ' + data.title);
      var container = document.getElementById("waitingDiv");
      window.location.href='joinQuiz.php?replay=yes&game_id='+data.title;

          });

  },
  function() {
    console.warn('WebSocket connection closed');
  },
  {'skipSubprotocolCheck': true}
);
}

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}
function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}
function hexToRgb(hex) {
    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
    } : null;
}
