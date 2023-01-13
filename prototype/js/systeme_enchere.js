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


var contextClass = (window.AudioContext || window.webkitAudioContext || window.mozAudioContext || window.oAudioContext || window.msAudioContext);
var dance = document.getElementById("encherebutton");
if (contextClass) {
  var context = new contextClass();
} else {
  onError;
}
var request = new XMLHttpRequest();
request.open('GET', "https://s3-us-west-2.amazonaws.com/s.cdpn.io/4273/gonna-make-you-sweat.mp3", true);
request.responseType = 'arraybuffer';
request.onload = function() {
 context.decodeAudioData(request.response, function(theBuffer) {
  buffer = theBuffer;
  }, onError);
}
request.send();

function onError() { console.log("Bad browser! No Web Audio API for you"); }

function unpress() { dance.classList.remove("pressed"); }

function playSound() {
 dance.classList.add("pressed");
  var source = context.createBufferSource();
  source.buffer = buffer;
 source.connect(context.destination);
  source.start(0);
  var delay = 2000;
  setTimeout(unpress,delay);
}
dance.addEventListener('click', function(event) { playSound(); });



