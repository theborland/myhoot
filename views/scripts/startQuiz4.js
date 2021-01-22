var continents = ['sm_state_SA', 'sm_state_NA', 'sm_state_EU', 'sm_state_AF', 'sm_state_NS', 'sm_state_SS', 'sm_state_ME', 'sm_state_OC'];
var selected = 8;
var games = ['gsGeo', 'gsPlaces', 'gsPT', 'gsFlags', 'gsFacts', 'gsTemp','gsHist', 'gsEstimation', 'gsBrain', 'gsScience', 'gsSports', 'gsEntertainment','gsCustom'];

//  adds the onClick functions to all the game types
function initChecks() {
  //,'gsEstimation'
  document.getElementById("customPicker").onchange = function() {
     var v=document.getElementById("customPicker").value;
     document.getElementById("customID").value=v;
     document.getElementById('gs13').classList.add("gsSel");
     document.getElementById('gsCustom').value = "true";

  };

  for (var i = 0; i < 13; i++) {
    var gs = document.getElementById("gs" + (i + 1));
    var c = gs.className;
    gs.onclick = function() {
      var idNum = parseInt(this.id.substr(this.id.indexOf('gs') + 2)) - 1;
      var name = games[idNum];
      var cs = this.children;
      //alert (this.id.substr(this.id.indexOf('gs')+2));
      console.log(idNum);
      if (this.className.indexOf("gsSel") >= 0) {
        this.classList.remove("gsSel");
        document.getElementById(name).value = "false";
      } else {
        this.classList.add("gsSel");
        if (idNum==12)
        {
          document.getElementById('btnSelectAll').innerHTML ="deselect all";
          console.log(document.getElementById('btnSelectAll').innerHTML);
          selectAll();

        }
        else{
          document.getElementById('gs13').classList.remove("gsSel");
          document.getElementById('gs13').value = "false";
          document.getElementById('gsCustom').value = "false";

        }

        //alert (this.id.charAt(this.id.length-1));
        //alert(this.id.length);
        document.getElementById(name).value = "true";
      }
    }
  }
  selectAll();
}


function initRegions() {
  setTimeout(function() {
    for (var i = 0; i < continents.length; i++) {
      var gs = document.getElementsByClassName(continents[i]);
      //var c = gs.className;
      //alert(gs.className);
      gs[0].classList.add("pathSelected");
      gs[0].onclick = function() {
        if (((String)(this.classList)).indexOf("pathSelected") >= 0) {
          this.classList.remove("pathSelected");
          document.getElementById(((String)(this.classList)).split(" ")[0]).value = "false";
          selected--;
        } else {
          this.classList.add("pathSelected");
          document.getElementById(((String)(this.classList)).split(" ")[0]).value = "true";
          selected++;
        }
        animateSelectAll();
      }
    }

  }, 300);

}

function selectAll(){
  // var gs = document.getElementById("gs12");
  // var c = gs.className;
  // gs.onclick = function() {



    for (var i = 0; i < 12; i++) {
      var gsShow = document.getElementById("gs" + (i + 1));
      var name2 = games[i];
      if (document.getElementById('btnSelectAll').innerHTML == "deselect all") {
        gsShow.classList.remove("gsSel");
        document.getElementById(name2).value = "false";
      } else {
        gsShow.classList.add("gsSel");
        document.getElementById(name2).value = "true";
      }

    }
    if (document.getElementById('btnSelectAll').innerHTML == "deselect all")
      document.getElementById('btnSelectAll').innerHTML = "select all";
    else
      document.getElementById('btnSelectAll').innerHTML = "deselect all";



}

// animates the plane and the banner, given frame number.
function animatePlane(i) {
  document.getElementById('linkPlane').style.top = (20 + Math.sin(i / 20) * 15) + "px";
  document.getElementById('joinHere').style.top = (20 + Math.sin((i - 20) / 20) * 15) + "px";
  document.getElementById('bannerLink').style.top = (20 + Math.sin((i - 10) / 20) * 15) + "px";
}


function showSetting() {
  document.getElementById("settingBlur").style.display = "block";
  window.setTimeout(function() {
    document.getElementById("settingBlur").style.opacity = "1";
  }, 50);
  document.getElementById("settingWrap").style.opacity = "1";
  document.getElementById("settingWrap").style.top = "0px";
}

function hideSetting() {
  document.getElementById("settingBlur").style.opacity = "0";
  window.setTimeout(function() {
    document.getElementById("settingBlur").style.display = "none";
  }, 700);
  document.getElementById("settingWrap").style.opacity = "0";
  document.getElementById("settingWrap").style.top = '-700px';
}



function selectall() {
  for (var i = 0; i < continents.length; i++) {
    var gs = document.getElementsByClassName(continents[i]);
    if (((String)(gs[0].classList)).indexOf("pathSelected") >= 0 && selected > 4) {
      gs[0].classList.remove("pathSelected");
      document.getElementById(((String)(gs[0].classList)).split(" ")[0]).value = "false";
    } else if (((String)(gs[0].classList)).indexOf("pathSelected") < 0 && selected <= 4) {
      gs[0].classList.add("pathSelected");
      document.getElementById(((String)(gs[0].classList)).split(" ")[0]).value = "true";
    }
  }

  if (selected > 4) {
    selected = 0;
    document.getElementById('statesCB').checked = false;
  } else {
    selected = 8;
    document.getElementById('statesCB').checked = true;
  }

  animateSelectAll();
}

function animateSelectAll() {
  if (selected > 4) {
    document.getElementById('selectAll').value = "deselect all";
  } else {
    document.getElementById('selectAll').value = "select all";
  }

}

function removeUser(username) {
  console.log("user is..." + username);
  var fixed = username.replace(/912/g, "%");
  var fixed = username.replace(/913/g, ".");
  console.log("user is..." + fixed);
  $.ajax({
    url: "rejectUser?name=" + fixed
  }).done(function() {
    //username=username.replace(/%/g,"912");
    $('#user_w_name_' + username).animate({
      opacity: 0,
      width: "0px"
    }, 500, function() {
      $('#user_w_name_' + username).remove();
      numUsers.innerHTML = parseInt(numUsers.innerHTML) - 1;
    });

  });
}
