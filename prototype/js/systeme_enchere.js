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
    if(duree==0){
        clearInterval(Tempsrestant);
    }
}



