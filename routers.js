var express = require('express')
var app = express()
var dir = ".";
var socketIP = "https://gameon.world";
var Game = require("./controller/Game.js");
var async = require('async');
var socket = require("./app.js");
var Question = require("./controller/Question.js");
var Answer = require("./controller/Answer.js");
var User = require("./controller/User.js");
var CustomUser = require("./controller/CustomUser.js");

// var Data=require("./controller/Data.js");

// var con= require('./mysql.js')
// var Game=require(dir+'/controller/Game.js');
//console.log("ses id is ");



//req.getConnection(function(error, conn) {
// db.query("SELECT COUNT(id) as total FROM answers", function (err, result, fields) {
//   if (err) throw err;
//   var answers=""+result[0].total;
//   console.log("in main"+answers);
//   res.render(dir+'/index', {answersToDate: answers})
// });
// });"host": "ec2-54-157-68-153.compute-1.amazonaws.com",
//app.use(express.static(dir + '/views'));
app.get('/stats', function(req, res) {
  res.redirect('http://nano.gameon.world/controller/estimation/admin/stats.php?time=y');
});
app.get('/phpmyadmin2', function(req, res) {
  res.redirect('http://54.157.68.153//phpmyadmin');
});
app.get('/single', function(req, res) {
  res.redirect('http://nano.gameon.world/single/joinquizform');
});
app.get('/userScreen', function(req, res) {
  res.render(dir + '/userScreen', {
    qID: req.query.question,
    color: req.session.color,
    socketIP: socketIP,
    game_id: req.session.game_id
  })
  // console.log("in user screen");
  // console.log(req.session);
  var io = socket.getSocket();
  setTimeout(function() {
    io.to("host" + req.session.game_id + "-" + req.query.question).emit('anotherUser');
  }, 1000);
  //console.log("host" + req.session.game_id + "-" + req.query.question);
})

app.get('/startQuiz', function(req, res) {
  var lastGame_ID = req.session.game_id;
  Game.createGame(req, function(game_id) {
    if (game_id > 0) {
      req.session.game_id = game_id;
      if (req.query.replay == "yes") {
        var io = socket.getSocket();
        io.in(lastGame_ID).emit('replay', game_id.substring(0, 5));
        console.log(lastGame_ID);
      }
      res.render(dir + '/startQuiz', {
        game_id: game_id,
        socketIP: socketIP
      })

    }
  });
})

app.get('/waitingScreenEnd', function(req, res) {
  res.render(dir + '/waitingScreenEnd', {
    socketIP: socketIP,
    game_id: req.session.game_id
  });
})

app.get('/checkQuestion', function(req, res) {
  Game.findGame(req.session.game_id, function(err, theGame) {
    if (err || theGame == null)
      res.redirect('/');
    else if (theGame.round == null || theGame.round == -1)
      res.render(dir + '/waitingScreen', {
        socketIP: socketIP,
        game_id: req.session.game_id,
        user_id: "",
        message: "Hold your horses, there is no question in progess ",
        place: "",
        name: ""
      });
    else if (theGame.type == "geo" || theGame.type == "pt" || theGame.type == "places" || theGame.type == "flag" || theGame.type == "sports-teams" || theGame.type == "sports-teams")
      res.redirect('userScreen?question=' + theGame.round);
    else
      res.redirect('userScreenOther?question=' + theGame.round);
  });

})

app.get('/waitingScreen', function(req, res) {

  res.render(dir + '/waitingScreen', {
    socketIP: socketIP,
    game_id: req.session.game_id,
    user_id: "",
    message: req.query.message,
    place: "",
    name: ""
  });
})

app.get('/endScreen', function(req, res) {
  console.log("endscreen");
  async.waterfall( //you will need to check to see if question is still active and also get type
    [
      function loadUsers(callback) {
        console.log("loadusers");
        User.loadAllUsers(req.session.game_id, function(err, allUsers) {
          if (err) return callback(true);
          else {
            console.log(allUsers);
            callback(false, allUsers);
          }
        });
      }
    ],
    function showPage(err, allUsers) {
      console.log(allUsers);
      var data = {
        game_id: req.session.game_id,
        title: "end",
        type: "end"
      };
      var io = socket.getSocket();
      io.in(req.session.game_id).emit('newQ', data);
      console.log(req.session.auto);
      res.render(dir + '/endScreen', {
        game_id: req.session.game_id,
        allUsers: allUsers
      })

    }
  );
})


app.get('/getQuestion', function(req, res) {
  //req.query
  // console.log("GETTING IN GETQUESTION" + req.query);
  // console.log(req.query);
  var GL = require("./controller/GameLogic.js");
  async.waterfall(
    [function setUpGame(callback) {
        //console.log(req.session);
        if (req.query.gsGeo == 'false' || req.query.gsGeo == 'true')
          GL.setupGame(req.query, req.session, callback);
        else
          callback(null);
      },
      function selectQuestionType(callback) {
        GL.selectQuestion(req.session, callback);
      },
      function selectQuestion(type, callback) {
        console.log("selectque");
        // console.log(req.session); socket.emit('alertUsers', gameID,qID,type);
        new Question(type, req.session, callback);
      },
      function uniqueQuestion(question, callback) {
        console.log("checkingUnique");
        var returned=false;
        var allGames = socket.getGames();
        if (allGames['G' + req.session.game_id] && question.type.substring(0,6)!="custom"){
          questionsUsed=allGames['G' + req.session.game_id].questionsUsed;
          console.log("questions used is "+questionsUsed);
          console.log("checking"+ (question.type+question.qID))
          if (questionsUsed.includes((question.type+question.qID))){
                console.log("duplicate");
                req.session.questionNumber--;
                returned=true;
                new Question("geo", req.session, callback);
                //selectQuestion(type,callback);
              }
        }
        if (!returned)
            return callback(false, question);
        // question.checkRepeats(function(err) {
        //   if (err) {
        //     req.session.questionNumber--;
        //     return new Question(question.type, req.session, callback);
        //   } else return callback(false, question);
        // })
      }
    ],
    function timeToRender(err, question) {
      //lets add question to array
      console.log("rendering");
      //console.log(question);
      if (err == true && question == "gameover")
        res.redirect("endScreen");
      else if (err || question == null)
        res.redirect('/');
      else {
        var allGames = socket.getGames();
        var time = new Date().getTime();
        var questionsUsed=[];
        if (allGames['G' + req.session.game_id])
                questionsUsed=allGames['G' + req.session.game_id].questionsUsed;
        questionsUsed.push(question.type+question.qID);
        allGames['G' + req.session.game_id] = {
          time: time,
          question: question,
          questionsUsed:questionsUsed
        };
        allGames['G' + req.session.game_id].answers = [];
        // if (allGames['G717881037']!=null)
        // console.log("here it is");
        //  console.log(allGames);
        // //
        // console.log("got q render");
        //console.log("it is "+JSON.stringify(question))
        var io = socket.getSocket();
        question.notifyUsers(io);

        res.render(dir + '/getQuestion', {
          session: req.session,
          socketIP: socketIP,
          question: (question)
        })
        question.addQuestion();
        Game.updateRound(req.session.game_id, question.questionNumber, question.type);
        // Node.js and JavaScript Rock!
          console.log("DONE WITH GETQUESTION");
      }
    }

  );

})




app.post('/submitAnswer', function(req, res) {
  //req.query
  //req.session = req.body; //tmp

  console.log("Submitting an answer");
  // req.session.game_id ? "" : req.session.game_id = "100001026";
  var allGames = socket.getGames();
  var theGame = allGames['G' + req.session.game_id];
  // if (theGame == null) res.redirect("/");
  // console.log(allGames);
  // console.log("ZZZZZ")
  // console.log(theGame);
  // if (theGame.answers['U' + req.session.user_id] != null)
  //   console.log(theGame.answers['U' + req.session.user_id]);
  //var theGame;
  //console.log(req.session);
  async.waterfall( //you will need to check to see if question is still active and also get type
    [
      function checkStuff(callback) {
        var required = 0;
        var alreadyRet = false;
        // console.log(req);
        // console.log(req.body);
        //Game.findGame(req.session.game_id, function(err, g) {
        //console.log("return"+g.type);
        if (theGame == null) {
          if (!alreadyRet) {
            alreadyRet = true;
            return callback(true, "Your game is not really happenning");
          }
        } else if (theGame.question.questionNumber != req.body.questionNumber) {
          if (!alreadyRet) {
            alreadyRet = true;
            return callback(true, "Wrong Question?");
          }
        } else if (req.body.lat == null && req.body.answer == 0) {
          alreadyRet = true;
          return callback(true, "Fake Answer");
        } else {
          //theGame = g;
          required++;
          console.log("req is 1" + alreadyRet);
          if (required == 2 && !alreadyRet) {
            alreadyRet = true;
            return callback(false);
          }
        }

        //Answer.Answer.firstSubmit(req.session.game_id, req.session.user_id, req.body.questionNumber, function(err) {
        if (theGame != null && theGame.answers != null &&
          theGame.answers['U' + req.session.user_id] != null) {
          if (!alreadyRet) {
            alreadyRet = true;
            return callback(true, "already");
          }
        } else {
          required++;
          console.log("req is2 " + alreadyRet);
          if (required == 2 && !alreadyRet) {
            alreadyRet = true;
            return callback(false);
          }
        }

      },

      function findCorrect(callback) {
        //console.log(req.body);
        //console.log("fidning corroect");
        // var allGames = socket.getGames();
        // var theGame = allGames['G' + req.session.game_id];
        Answer.Answer.loadCorrectSimple(theGame.question, callback)
      },
      function findDistanceAway(correctAnswer, callback) {
        console.log("correct");
        console.log(correctAnswer);
        if (req.body.lat != null)
          var distanceAway = Answer.LatLong.findDistance(correctAnswer.location.lat, correctAnswer.location.longg, req.body.lat, req.body.long);

        else
          var distanceAway = Math.round(Math.abs(req.body.answer - correctAnswer.value) * 10) / 10;
        return callback(false, distanceAway);
      },
      function addAnswer(distanceAway, callback) {
        var io = socket.getSocket();
        //  console.log("adding answer");
        theGame.answers['U' + req.session.user_id] = 1;
        Answer.Answer.addAnswer(req.session.game_id, req.session.user_id, req.body.questionNumber, req.body.lat, req.body.long, req.body.answer,
          distanceAway, req.session.color,
          function(err, qID) {
            //console.log("done adding answer");
            if (err) return callback(true);
            else return callback(false, distanceAway, qID)
          });
        if (theGame.question.type == "pop")
          distanceAway = Math.round(distanceAway / 1000) * 1000;
        io.in("host" + req.session.game_id + "-" + req.body.questionNumber).emit('newA', decodeURI(req.session.name), Number(distanceAway).toLocaleString(), req.session.color);
        //tmp
        //console.log("sending emit");
        //return callback(false, distanceAway,"geo66")
      },
      function findPlace(distanceAway, qID, callback) {
        Answer.Answer.findPlace(distanceAway, qID, callback);
        //return callback(false, distanceAway);
      }
    ],
    function(err, distanceAway, place) {
      // console.log(err+distanceAway);
      if (err == false) {
        // console.log("thegame!!!");
        // console.log(req.session.name);
        res.render(dir + '/waitingScreen', {
          socketIP: socketIP,
          game_id: req.session.game_id,
          user_id: req.session.user_id,
          message: "Off by: " + Question.getUnitsAway(theGame.question.type, distanceAway),
          place: place,
          name: req.session.name
        });
      } else
        res.render(dir + '/waitingScreen', {
          socketIP: socketIP,
          game_id: req.session.game_id,
          user_id: req.session.user_id,
          message: distanceAway,
          place: "",
          name: req.session.name
        });

    }
    //console.log("it is "+JSON.stringify(question))
    //console.log("Dist away" + distanceAway);
    //console.log(distanceAway);
    //res.render(dir+'/getQuestion', {session: req.session,socketIP:"http://"+socketIP,question:(question)})
    // Node.js and JavaScript Rock!

  );

})
app.get('/showAnswer', function(req, res) {
  //req.session.game_id='589811035'; //tmp
  console.log("showing answer!@!");
  var io = socket.getSocket();
  async.waterfall( //you will need to check to see if question is still active and also get type
    [
      function loadQ(callback) {
        console.log("loadq");
        if (req.session.game_id == null || req.session.game_id < 10)
          return callback(true);
        else
          Question.loadQuestion(req.session.game_id, callback);
      },
      function loadCorrect(question, callback) {
        console.log("loadc" + question.questionNumber);
        question.notifyUsers(io, false);
        Game.updateRound(req.session.game_id, -1, null);
        question.loadImage();
        Answer.Answer.loadCorrect(question.questionNumber, req.session.game_id, function(err, result) {
          if (err == true) return callback(true);
          return callback(false, question, result);
        })
      },
      function loadUsers(question, correct, callback) {
        console.log("loadusers");
        User.loadAllUsers(req.session.game_id, function(err, allUsers) {
          if (err) return callback(true);
          console.log(allUsers);
          callback(false, question, correct, allUsers);
        });
      },
      function loadAnswers(question, correct, allUsers, callback) {
        Answer.Answers.loadAnswers(allUsers, question.questionNumber, req.session.game_id, function(err, pointsAwarded) {
          if (err) return callback(true);

          callback(false, question, correct, allUsers, pointsAwarded);
        });
      },
      function awardPoints(question, correct, allUsers, pointsAwarded, callback) {
        if (pointsAwarded == false)
          User.awardPoints(allUsers);
        callback(false, question, correct, allUsers);
      }
    ],
    function showPage(err, question, correct, allUsers) {
      //  console.log(allUsers);
      console.log("showing page");
      if (err || question == null)
        res.redirect("/");
      User.sendPlaceComment(req.session.game_id, allUsers, io);
      //allUsers.sort(allUsers.compareDistanceAway);
      if (question.type == "geo" || question.type == "places" || question.type == "pt" || question.type == "flag" || question.type == "sports-teams" || question.type == "custom-geo") {
        res.render(dir + '/showAnswer', {
          game_id: req.session.game_id,
          zoomLevel: User.zoomLevel(allUsers),
          correctAns: correct,
          allUsers: allUsers,
          theQuestion: question,
          numRounds: req.session.numRounds,
          auto: req.session.auto
        })
      } else {
        var range = question.findRange();
        var splits = range.match(/\[(.*?)\]/g);
        var min = splits[0].replace(/\[|\]/g, "");
        var max = splits[splits.length - 1].replace(/\[|\]/g, "");
        //console.log(max);
        res.render(dir + '/showAnswerOther3', {
          game_id: req.session.game_id,
          zoomLevel: User.zoomLevel(allUsers),
          correctAns: correct,
          allUsers: allUsers,
          theQuestion: question,
          numRounds: req.session.numRounds,
          auto: req.session.auto,
          min: min,
          diff: max - min

        })
      }
    }
  );
})

app.get('/userScreenOther', function(req, res) {
  async.waterfall( //you will need to check to see if question is still active and also get type
    [
      function loadQ(callback) {
        console.log("loadq");
        var allGames = socket.getGames();
        var theGame = allGames['G' + req.session.game_id];
        //console.log(theGame);
        if (theGame == null)
          return callback(true);
        else
          Question.loadQuestionSimple(theGame.question, callback);
      }
    ],
    function showPage(err, question) {
      if (err || question == null)
        res.redirect("/");
      var range = question.findRange();
      var splits = range.match(/\[(.*?)\]/g);
      var min = splits[0].replace(/\[|\]/g, "");
      var max = splits[splits.length - 1].replace(/\[|\]/g, "");
      var startingVal = splits[Math.floor((splits.length / 2))].replace(/\[|\]/g, "");
      console.log("ready to show!!!");
    //  console.log(question);
      var io = socket.getSocket();
      setTimeout(function() {
        io.to("host" + req.session.game_id + "-" + req.query.question).emit('anotherUser');
      }, 1000);
      var type = question.type;
      if (question.answer < 2020 && question.answer > 1850) type = "time";
      console.log("about to render");
      res.render(dir + '/userScreenOther', {
        questionNumber: question.questionNumber,
        startValue: startingVal,
        roundingValue: question.findRoundingValue(),
        ranges: range,
        units: question.findUnit(),
        bgcolor: question.findBGColor(),
        type: type,
        max: max,
        min: min,
        inputValueSize: question.findInputSize(),
        answer: question.answer,
        socketIP: socketIP,
        game_id: req.session.game_id
      })
    }
  );
})
app.get('/rejectUser', function(req, res) {
  console.log("trying to remove");
  var name = encodeURI(req.query.name);
  var io = socket.getSocket();
  var data = {
    title: "R" + name
  };
  //console.log(data);
  io.in(req.session.game_id).emit('reject', data);
  var db = require('./db.js');
  db.getQuery("DELETE FROM `users` WHERE game_id ='" + req.session.game_id + "' AND name='" + name + "'", function(err, results) {
    if (err)
      console.log("problem removing");
    else {
      console.log("removed " + name);
    }
  });
  res.send("removed");
})

app.get('/test', function (req, res) {
  var url = 'https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=41.43206,-81.38992&destinations=New+York+City,NY&key=AIzaSyDFCvK3FecOiz5zPixoSmGzPsh0Zv75tZs';
  var request = require('request');
  request(url, function(error, response, body) {
    res.send('hello world'+response+body);
  })

})

app.get('/clean', function(req, res) {
  var db = require('./db.js');
  db.cleanAnswers(function(t){});

})


app.get('/contactUs', function(req, res) {
  console.log("trying contacting");
  res.render(dir + '/contactUs', {
    mailResponse: ""
  })
})

app.post('/contactUs', function(req, res) {
  var nodemailer = require('nodemailer');
  var transporter = nodemailer.createTransport({
    service:'gmail',
    port:25,
    secure:false,
    requireTLS:true,
    auth: {
      user: 'deeringcs@gmail.com',
      pass: 'GameOn.World'
    }
  });
  var mailOptions = {
    from: 'deeringcs@gmail.com',
    to: 'theborland@gmail.com',
    subject: 'Comment from GameOn',
    text: "From: " + req.body.name + "\n E-Mail: " + req.body.email + "\n Message:\n " + req.body.message
  };
  if (req.body.math == 3) {
    transporter.sendMail(mailOptions, function(error, info) {
      if (error) {
        console.log(error);
      } else {
        console.log('Email sent: ' + info.response);
      }
    });
    res.render(dir + '/contactUs', {
      mailResponse: "Message sent - thanks for the feedback"
    })
  } else {
    res.render(dir + '/contactUs', {
      mailResponse: "Please fill out the math question!!!"
    })
  }
})

app.get('/joinQuizForm', function(req, res) {
  var game_id = req.query.game_id;
  if (game_id != null) game_id = game_id.substring(0, 5);
  var name = decodeURIComponent(req.session.name);
  res.render(dir + '/joinQuizForm', {
    game_id: game_id,
    error: req.query.message,
    name: name
  })
})
app.get('/joinQuiz', function(req, res) {
  //console.log(req.query);
  console.log("hellowaiting" + encodeURI(req.query.name));
  req.query.name = req.query.name.trim();
  req.query.name = encodeURI(req.query.name);
  var blankNames = ["SpaceMan", "Space Cowboy", "Dr. Spa-ce-Man", "2 Cool 4 Name", "Whats my name again?"];
  if (req.query.name == "") req.query.name = blankNames[Math.floor(Math.random() * blankNames.length)];;
  req.session.game_id = Game.findGameID(req.query.game_id);
  Game.findGame(req.session.game_id, function(err, result) {
    if (err) {
      res.render(dir + '/joinQuizForm', {
        error: "Bad Game",
        game_id: "",
        name: decodeURI(req.query.name)
      })
    } else {
      User.createUser(req.query.game_id, req.query.name, req.session, req, function(err) {
        if (err == true) {
          res.render(dir + '/joinQuizForm', {
            error: "Bad Username",
            game_id: req.query.game_id,
            name: ""
          })
        }
        if (err == false) {
          // console.log("add users " + req.query.name + " to " + "host" + req.session.game_id);

          var io = socket.getSocket();
          //notifyNewUserInGame(req.query.game_id,req.query.name,'00fffff');

          io.in("host" + req.session.game_id).emit('newUser', req.query.name, req.session.color);
          // console.log("add users " + req.query.name + " to " + "host" + req.session.game_id);
          res.render(dir + '/waitingScreen', {
            socketIP: socketIP,
            game_id: req.session.game_id,
            user_id: "",
            message: "new",
            place: "",
            name: decodeURI(req.query.name)
          });
        }
      });

    }
  });
});


app.get('/', function(req, res) {
  console.log("req" + req.url);
  //console.log("getting d" +Data.getData());
  var db = require('./db.js')
  if (req.device === 'phone')
    res.redirect('joinQuizForm');
  else {
    db.getNumAnswers(function(err, results) {
      if (err) {
        console.log(err);
        return res.render(dir + '/index', {
          answersToDate: ""
        })
      }
      var answers = results;
      res.render(dir + '/index', {
        answersToDate: answers
      })
      //console.log("ses id is " + req.session.game_id);
    });
  }
})

app.post('/registerToken', function(req, res) {
  var id=req.body.id;
  req.session.remember_token=id;
  console.log(req.session);
  req.session.save();
  res.send({"id":id});

});

app.get('/startQuizNew', function(req, res) {
  console.log("startQuizNew");
  async.waterfall( //you will need to check to see if question is still active and also get type
    [
      function loadCustomUser(callback) {
        var db = require('./db.js')
        db.getQuery("SELECT * FROM `customUsers` WHERE remember_token ='" + req.session.remember_token + "'", function(err, results) {
          if (err)
              return callback(true);
          else if(results.length==0){
                var user=null;
          }
          else{
              req.session.customUsername=results[0].name;
              req.session.customUserID=results[0].id;
              var user=new CustomUser.CustomUser(req.session.customUserID,req.session.customUsername);
          }
          return callback(false,user);
      });
      },
      function loadLastFive(user,callback){
        var customQuizzes= new Array();
        if (user==null){
            return callback(false,user,customQuizzes);
        }
        else{
          user.find5MostRecentQuiz(function(err, customQuizzes) {
               if (err)
                  return callback(true);
               else return callback(false,user,customQuizzes);
          });
        }

      },
      function getGameID(user,customQuizzes,callback){
        Game.createGame(req, function(game_id) {
          if (game_id > 0){
            req.session.game_id = game_id;
            return callback(false,user,customQuizzes,game_id);
          }            
          else
            return callback(true);
          });
        }
    ],
    function showPage(err, user,customQuizzes,game_id) {
      if (req.query.replay == "yes") {
        var io = socket.getSocket();
        io.in(lastGame_ID).emit('replay', game_id.substring(0, 5));
        console.log(lastGame_ID);
      }
      res.render(dir + '/startQuizNew', {
        game_id: game_id,socketIP: socketIP,customQuizzes:customQuizzes,
        custom:req.query.custom
      })

    }
  );
})


app.get('/indexNew', function(req, res) {
  var db = require('./db.js')
  db.getQuery("SELECT * FROM `customUsers` WHERE remember_token ='" + req.session.remember_token + "'", function(err, results) {
    if (err)
      console.log("no user");
    else {
      req.session.customUsername=results[0].name;
      req.session.customUserID=results[0].id;
    }
  });
  console.log("req" + req.url);
  //console.log("getting d" +Data.getData());

  if (req.device === 'phone')
    res.redirect('joinQuizForm');
  else {
    db.getNumAnswers(function(err, results) {
      if (err) {
        console.log(err);
        return res.render(dir + '/indexNew', {
          answersToDate: ""
        })
      }
      var answers = results;
      res.render(dir + '/indexNew', {
        answersToDate: answers,
        remember_token:req.session.customUsername
      })
      //console.log("ses id is " + req.session.game_id);
    });
  }
})

app.get('*', function(req, res) {
  res.redirect('/');
});
/**
 * We assign app object to module.exports
 *
 * module.exports exposes the app object as a module
 *
 * module.exports should be used to return the object
 * when this file is required in another module like app.js
 */
module.exports = app;
