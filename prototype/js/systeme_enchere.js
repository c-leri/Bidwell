var Tempsrestant;
var duree=216000;
function countdown(){
    Tempsrestant = setInterval(timer,10);
}

function timer(){
    --duree;
    var centsecondes=duree%60;
    var esecondes=(duree-centsecondes)/60;
    var secondes=esecondes % 60;
    var minutes = (esecondes-secondes)/60;
    console.clear();
    console.log(minutes+":"+secondes+":"+centsecondes);
    //document.getElementById("Rebours").innerHTML = minutes+":"+secondes+":"+centsecondes;
    if(duree==0){
        clearInterval(Tempsrestant);
    }
}


var enchere = document.getElementById("encherebutton");

var request = new XMLHttpRequest();
request.open('GET', "", true);
request.responseType = 'arraybuffer';
request.onload = function() {
  document.getElementById("informations")
}
request.send();




