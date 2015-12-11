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
        });
    },
    function() {
        console.warn('WebSocket connection closed');
    },
    {'skipSubprotocolCheck': true}
    );
  }
  function loadWaitingForQuestion(ip){
    var conn = new ab.Session('ws://'+ip+':8080',
    function() {
      conn.subscribe('Game<?php echo $_SESSION["game_id"] ?>Status', function(topic, data) {
        console.log('Waiting for users:"' + topic + '" : ' + data.title);
        var container = document.getElementById("waitingDiv");
        container.innerHTML = container.innerHTML  + "<br>"+data.title;
        if (data.title.substring(0,1)=="done")
        window.location.href='waitingScreen.php';
      });
    },
    function() {
      console.warn('WebSocket connection closed');
    },
    {'skipSubprotocolCheck': true}
  );
}
