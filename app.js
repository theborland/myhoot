// app.js
var cookieSession = require('cookie-session');
var express = require('express');
var session = require('express-session')
var app = express();
//var fs=require('fs');
// var sslOptions={
//   key:fs.readFileSync("/etc/letsencrypt/live/gameon.world/privkey.pem"),
//   cert:fs.readFileSync("/etc/letsencrypt/live/gameon.world/fullchain.pem")
// };
var server = require('http').createServer(app);
server.listen(4200);
var io = require('socket.io')(server);
var async = require('async');
var os = require("os");
var bodyParser = require('body-parser')
var responsive = require('express-responsive');
app.use(bodyParser.json()); // to support JSON-encoded bodies
app.use(bodyParser.urlencoded({ // to support URL-encoded bodies
  extended: true
}));

app.set('view engine', 'ejs')
app.set('trust proxy', true);
app.use(responsive.deviceCapture()); //to get screen size
// var Data=require("./controller/Data.js");

// console.log("getting d" +Data.getData());

// app.use(session({
//   secret: '324sdv',
//   resave: false,
//   saveUninitialized: true
// }))

app.use(cookieSession({
  secret: 'sdf325a',
  signed: true,
}));

app.use(function(req, res, next) {
  res.header("Access-Control-Allow-Origin", "https://users.gameon.world");
  res.header("Access-Control-Allow-Headers", "Origin, X-Requested-With, Content-Type, Accept");
  res.header('Access-Control-Allow-Credentials', 'true');
  next();
});

var db = require('./db.js');
db.cleanAnswers(function(err, results) {
  if (err)
    console.log("problem with cleaning db");
  else {
    console.log("answers total is found");
  }
});


// var mysql = require('mysql');
// var myConnection  = require('express-myconnection')
// var dbOptions = {
//     host:      "localhost",
//     user:       "root",
//     password: "myhoot",
//     port:       3306,
//     database: "MyHoot"
// }
// app.use(myConnection(mysql, dbOptions, 'pool'))

console.log("starting server");

var routers = require('./routers.js')
app.use('/', routers)
//app.use('/*', routers)
//app.use(express.static(dir + '/'));
// app.get('/', function(req, res,next) {
//
//   res.render(dir + '/index.ejs', {title: 'My Node.js Application'})
//   //  res.sendFile(dir + '/index.html');
// });
var allGames = [];
app.set('allGames', allGames);
module.exports.getGames = function() {
  return allGames;
}

module.exports.getSocket = function() {
  return io;
}
io.on('connection', function(client) {
  console.log('Client connected...');
  // _socket=client;
  client.on('create', function(data) {
    client.join("host" + data);
    console.log("listening on " + "host" + data);
    console.log("Creating a new game:" + data);
  }); //newUser', gameID,name,color
  client.on('newUser', function(gameID, name, color) {
    //client.join(gameID);
    console.log("Adding user:" + name + " to game:" + gameID);
    client.broadcast.to("host" + gameID).emit('newUser', name, color);
  });
  client.on('alertUsers', function(gameID, qID, type) {
    client.join("host" + gameID);
    console.log("Alerting users about:" + qID + " of game:" + gameID);
    client.broadcast.to(gameID).emit('newQ', qID, type);
  });
  client.on('waiting', function(gameID) {
    console.log("Another User waiting for question of game:" + gameID);
    client.join(gameID);
  });
  client.on('waitingAnswers', function(gameID, qID) {
    console.log("Host waiting for answers of question: " + qID + " game:" + gameID + " host" + gameID + "-" + qID);
    client.join("host" + gameID + "-" + qID);
  });
  client.on('submitAnswers', function(gameID, qID, name, miles, color) {
    console.log("Adding answer to q:" + qID + " of game:" + gameID + "miles" + miles + "-color" + color);
    client.broadcast.to("host" + gameID + "-" + qID).emit('newA', name, miles, color);
  });

  client.on('inQuestion', function(gameID, qID) {
    console.log("A user is in question:" + qID + " of game:" + gameID);
    client.broadcast.to("host" + gameID + "-" + qID).emit('anotherUser');
  });
  client.on('replay', function(gameID, newGameID) {
    console.log("Replay");
    client.broadcast.to(gameID).emit('replay', newGameID);
  });

  //waitingAnswers
  client.on('join', function(data) {
    client.join(data);
    console.log(data);
  });
  client.on('send', function(id, data) {
    client.broadcast.to(id).emit('messages', data);
    console.log(data);
  });
});


/*
var interval = 0;
myVar = setInterval(myTimer, 1000);


function myTimer() {
  var start = new Date().getTime();
  for (var i = 0; i < 20; i++) {
    var db = require('./db.js')
    db.getNumAnswers(function(err, results) {
      if (err) {
        console.log(err);

      }
      interval++;
      var elapsed = new Date().getTime() - start;
      console.log(elapsed);
      var answers = results;
      if (interval >= 100) clearInterval(myVar);
      console.log(interval + ": " + answers);
    });
  }
}
*/
