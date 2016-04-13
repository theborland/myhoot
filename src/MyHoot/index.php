<html>
<head>

	<title>GameOn.World</title>

	<link rel="stylesheet" href="style/global.css">
	<link rel="stylesheet" href="style/content.css">
	<script src="http://autobahn.s3.amazonaws.com/js/autobahn.min.js"></script>
	<script src="scripts/startQuiz.js"></script>
	<script src="scripts/global.js"></script>
	<script src="scripts/socketScripts.js"></script>



  <script type="text/javascript">

		window.onload = function(){
			
			  if (screen.width <= 800)
			    window.location = "joinQuiz.php";
			  /*else
			    window.location = "startQuiz.php";*/

			//set up clouds
		}



</script>

</head>
<body>

<div id="headWrap">
	<div id="logoWrap">
		<img src="img/logo.svg" id="logo">
	</div>
	<div id="menuWrap">
		<div id="menuItemContainer">
			<div class="menuItem"><a href="" class="menuLink">Play!</a></div>
			<div class="menuItem"><a href="" class="menuLink">About</a></div>
			<div class="menuItem"><a href="" class="menuLink">Contact</a></div>
		</div>
	</div>
</div>

<div id="clouds">

</div>

</body>
</html>
