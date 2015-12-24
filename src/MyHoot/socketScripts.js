function loadWaitingForUsers(ip,gameID){
    var conn = new ab.Session('ws://'+ip+':8080',
    function() {
    //  alert('Game'+gameID);
        conn.subscribe('Game'+gameID, function(topic, data) {
            console.log('Waiting for users:"' + topic + '" : ' + data.title);
          var container = document.getElementById("nameUsers");
    container.innerHTML =data.title   + "<br>"+container.innerHTML;
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
function loadWaitingForAnswers(ip,gameID,questionNumber){
    var conn = new ab.Session('ws://'+ip+':8080',
    function() {
        conn.subscribe('Game'+gameID+''+questionNumber+'', function(topic, data) {
            console.log('Getting answers:"' + topic + '" : ' + data.title);
            var numAnswers = document.getElementById("numAnswers");
            numAnswers.innerHTML = parseInt(numAnswers.innerHTML)  + 1;
            var userAnswers = document.getElementById("userAnswers");
            userAnswers.innerHTML = userAnswers.innerHTML  + '<div class="uaItem"><div class="uLabel">'+data.title+'</div><div class="uScore">'+data.miles+'</div></div>';
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
        console.log('Waiting for users:"' + topic + '" : ' + data.title);
        var container = document.getElementById("waitingDiv");
        //container.innerHTML = container.innerHTML  + "<br>"+data.title;
        /* not sure why this is here?
        if (data.title.substring(0,1)=="done")
        window.location.href='waitingScreen.php'; */
        if (data.title.substring(1)=="-1")
          window.location.href='waitingScreen.php';
        else if (data.title.substring(0,1)=="Q")
            window.location.href='userScreen.php?question='+data.title.substring(1);
            });

    },
    function() {
      console.warn('WebSocket connection closed');
    },
    {'skipSubprotocolCheck': true}
  );
}


/*
var conn = new ab.Session('ws://<?php echo $pusherIP ?>:8080',
function() {
    conn.subscribe('Game<?php echo $_SESSION["game_id"] ?>Status', function(topic, data) {
        console.log('Waiting for users:"' + topic + '" : ' + data.title);
      var container = document.getElementById("waitingDiv");
container.innerHTML = container.innerHTML  + "<br>"+data.title;
if (data.title.substring(0,1)=="Q")
  window.location.href='userScreen.php?question='+data.title;
    });
},
function() {
    console.warn('WebSocket connection closed');
},
{'skipSubprotocolCheck': true}
);
*/
