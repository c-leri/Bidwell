

window.setInterval(function(){

    valeur =parseFloat(document.getElementById('act').innerHTML);
    mini = parseFloat(document.getElementById('min').innerHTML);
    maxi = parseFloat(document.getElementById('max').innerHTML);

    document.getElementById('act').innerHTML =  valeur-(1/3600*maxi).toFixed(2);

    if (mini < valeur) {
    affichage = Math.round((1 -(valeur - mini)  / (maxi-mini)) * 74);

    document.getElementById('circle-container__progress').style.strokeDashoffset = affichage;
    }
  }, 1000);