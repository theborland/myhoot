<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>GameOn.World</title>

	<style>
		body{
			font-family:Roboto;
			padding: 0px;
			margin: 0px;
			width: 100%;
			height: 100%;
			box-sizing: border-box;
			background: #CAA2EE;
		}
		#page{
			position: absolute;
			top: 0px;
			right: 0px;
			left: 0px;
			bottom: 0px;
			width: 100%;
			height: 100%;
		}
	</style>

	<script>

		function fullscreen(){
		  if ((document.fullScreenElement && document.fullScreenElement !== null) ||    
		   (!document.mozFullScreen && !document.webkitIsFullScreen)) {
		    if (document.documentElement.requestFullScreen) {  
		      document.documentElement.requestFullScreen();  
		    } else if (document.documentElement.mozRequestFullScreen) {  
		      document.documentElement.mozRequestFullScreen();  
		    } else if (document.documentElement.webkitRequestFullScreen) {  
		      document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);  
		    }  
		  } else {  
		    if (document.cancelFullScreen) {  
		      document.cancelFullScreen();  
		    } else if (document.mozCancelFullScreen) {  
		      document.mozCancelFullScreen();  
		    } else if (document.webkitCancelFullScreen) {  
		      document.webkitCancelFullScreen();  
		    }  
		  }  
		}

	</script>
</head>
<body>
	<embed id="page" src="startQuiz.php" allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true">
</body>
</html>