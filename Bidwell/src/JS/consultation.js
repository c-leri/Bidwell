window.setInterval(function(){
   valeur =parseFloat(document.getElementById('act').innerHTML);
   mini = parseFloat(document.getElementById('min').innerHTML);
   maxi = parseFloat(document.getElementById('max').innerHTML);
   titre = document.getElementById('dateTitle').innerText;
   dates = document.getElementById('temps').innerHTML.split(":");

   if (titre.includes("commencera ")){
    date = new Date(0,0,0, dates[0], dates[1], dates[2] -1);
    document.getElementById('temps').innerHTML = date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds();

   } else if (mini < valeur){
    document.getElementById('act').innerHTML =  (valeur-(1/3600*maxi)).toFixed(2);
    
    date = new Date(0,0,0, dates[0], dates[1], dates[2] -1);
    document.getElementById('temps').innerHTML = date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds();

    affichage = ((1 -(valeur - mini)  / (maxi-mini)) * 74).toFixed(2);

    document.getElementById('circle-container__progress').style.strokeDashoffset = affichage;
  } 
  else if (date == "0:0:0"){
    location.reload();  
  }
  
  }, 1000);