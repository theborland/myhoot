var playing = true;
var selected = 8;
var continents = ['sm_state_SA', 'sm_state_NA', 'sm_state_EU', 'sm_state_AF', 'sm_state_NS','sm_state_SS','sm_state_ME','sm_state_OC'];

function showmap(){
	document.getElementById("mapBlur").style.display = "block";
	document.getElementById("mapBlur").style.opacity = "1";
	document.getElementById("mapWrap").style.top = 0;

}

function hidemap(){
	document.getElementById("mapBlur").style.display = "none";
	document.getElementById("mapWrap").style.top = -500;
}

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

function selectall(){
    for(var i = 0; i < continents.length; i++) {
        var gs = document.getElementsByClassName(continents[i]);
        if(((String)(gs[0].classList)).indexOf("pathSelected") >= 0 && selected>4){
        	gs[0].classList.remove("pathSelected");
        	document.getElementById(((String)(gs[0].classList)).split(" ")[0]).value = "false";
        }else if(((String)(gs[0].classList)).indexOf("pathSelected") < 0 && selected <= 4){
        	gs[0].classList.add("pathSelected");
        	document.getElementById(((String)(gs[0].classList)).split(" ")[0]).value = "true";
        }
    }

    if(selected>4){
    	selected = 0;
    }else{
    	selected = 8;
    }

    document.getElementById('statesCB').checked = !document.getElementById('statesCB').checked;

    animateSelectAll();
}

function animateSelectAll(){
    if(selected>4){
    	document.getElementById('selectAll').style.backgroundImage = "url('img/uncheckall.png')";
    }else{
    	document.getElementById('selectAll').style.backgroundImage = "url('img/checkall.png')";
    }

}