function mute(){
	if(playing==true)
		muteOff();
	else
		muteOn();
}

function muteOn(){
	var music = document.getElementById("bgMusic");
	var button = document.getElementById("muteButton");
	music.volume = 1;
	playing = true;
	button.style.backgroundImage = "url(img/mute1.png)";
	document.cookie="playMusic=true";
}

function muteOff(){
	var music = document.getElementById("bgMusic");
	var button = document.getElementById("muteButton");
	music.volume = 0;
	playing = false;
	button.style.backgroundImage = "url(img/mute2.png)";
    document.cookie="playMusic=false";
}
