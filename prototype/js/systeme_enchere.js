var Tempsrestant;
var duree=3600;
function countdown(){
    Tempsrestant = setInterval(timer,1000);
}

function timer(){
    --duree;
    var secondes=duree%60;
    var minutes=(duree-secondes)/60;
    console.clear();
    console.log(minutes + ":"+secondes);
    if(duree==0){
        clearInterval(Tempsrestant);
    }
}

countdown();

