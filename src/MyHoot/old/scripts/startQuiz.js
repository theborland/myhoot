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