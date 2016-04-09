class Cloud{

    constructor(id, docHeight){
        this.id=id;
        this.numBoxes =     Math.floor( Math.random() * 3 ) + 1;
        this.overallTop =   Math.floor( Math.random() * docHeight * 0.6);
        this.overallLeft = -300;
        document.getElementById("clouds").innerHTML = document.getElementById("clouds").innerHTML + "<div class='cloudWrap' id='cloudWrap_" + this.id + "'>";
        for(var i=0; i<this.numBoxes; i++){
            document.getElementById("clouds").innerHTML = document.getElementById("clouds").innerHTML + "<div class='cloud' id='cloud_" + this.id + "_" + i + "''></div>";
        }
        document.getElementById("clouds").innerHTML = document.getElementById("clouds").innerHTML + "</div>";
    }

    animate(x){
        for(var i=0; i<this.numBoxes; i++){
            var left = document.getElementById("cloud_" + this.id + "_" + i ).style.left;
            var leftInt = parseInt(left);
            document.getElementById("cloud_" + this.id + "_" + i ).style.left = (leftInt+1)+"px";
        }
    }
    destroy(){

    }
}

