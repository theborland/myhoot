<html>
<head>

	<title>Game On, World!</title>

	<link rel="stylesheet" href="style/global.css">
	<link rel="stylesheet" href="style/startQuiz.css">
	<script src="http://autobahn.s3.amazonaws.com/js/autobahn.min.js"></script>
	<script src="scripts/startQuiz.js"></script>
	<script src="scripts/global.js"></script>
	<script src="scripts/socketScripts.js"></script>
	<script src="scripts/cloud.js"></script>



  <script type="text/javascript">
		var clouds={};
		var numClouds=1;
		var actClouds=1;

		window.onload = function(){
			
			  if (screen.width <= 800)
			    window.location = "joinQuiz.php";
			  /*else
			    window.location = "startQuiz.php";*/

			//set up clouds
			var docHeight = (document.height !== undefined) ? document.height : document.body.offsetHeight;
			var docWidth = screen.width;
			clouds[0] = new Cloud(0, docHeight, docWidth);

			//animation clock
			var x=0;
			var interval = setInterval(function() {
			     animateClouds(x);

			     if(Math.random()<0.002){
			     	clouds[numClouds] =  new Cloud(numClouds, docHeight, docWidth);
			     	numClouds++;
			     	actClouds++;
			     }
			     x++;
			}, 50);


		}

		function animateClouds(x){
			var docWidth = screen.width;
			for(var i=(numClouds-actClouds); i<numClouds; i++){
			    clouds[i].animate(x);
			    /*if(clouds[i].destroy(docWidth)){
			    	actClouds--;
			    	alert(actClouds + " out of " + numClouds + " left")
			    }*/
			}
		}




</script>

</head>
<body>

<div id="headWrap">
	<div id="logoWrap">
		<img src="img/logo.svg" id="logo">
	</div>

</div>

<div id="clouds">

</div>

</body>
</html>
