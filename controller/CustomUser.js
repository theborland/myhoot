var db = require('../db.js');
var Question = require("./Question.js");

//db.connectDatabase();
class CustomUser {

  constructor(id, name) {
    this.id = id;
    this.name = name;

    }

  find5MostRecentQuiz(callback)
  {
    // console.log("sdfsdf"+this.name);
    //console.log("SELECT * FROM `customQuizzes` WHERE userID ='" +this.id+ "' ORDER BY `updated_at` DESC")

    db.getQuery("SELECT * FROM `customQuizzes` WHERE userID ='" +this.id+ "' ORDER BY `updated_at` DESC LIMIT 5", function(err, results) {
      if (err){
          console.log("no quiz");
          return callback(true);
      }

      else {
        var quizzes = new Array();
        for (var i = 0; i < results.length; i++)
        {
          var name=results[i].name;
          var quizID=results[i].id;
          //  console.log(results[i]);
          //  var img=Question.getRandomUrl(results[0].image);
          quizzes.push(new CustomQuiz(quizID,name,""));

        }
        return callback(false,quizzes);

      }
    });
  }


}

class CustomQuiz {
  constructor(id, name,img) {
    this.id = id;
    this.name = name;
    this.img=img;
  }
}

module.exports = {
  CustomUser: CustomUser,
  CustomQuiz: CustomQuiz
}
