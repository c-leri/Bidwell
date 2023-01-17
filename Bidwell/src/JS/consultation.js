window.setInterval(function () {
  valeur = parseFloat(document.getElementById('act').innerHTML);
  mini = parseFloat(document.getElementById('min').innerHTML);
  maxi = parseFloat(document.getElementById('max').innerHTML);
  titre = document.getElementById('dateTitle').innerText;
  date = document.getElementById('temps').innerText;
  dates = document.getElementById('temps').innerText.split(":");

  console.log(date);


  if (date.includes("0:0:0") || date.includes("00:00:00")) {
    location.reload();
  } else if (titre.includes("commencera ")) {

    date = new Date(0, 0, 0, dates[0], dates[1], dates[2] - 1);
    document.getElementById('temps').innerHTML = date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds();

  } else if (mini < valeur) {
    document.getElementById('act').innerHTML = (valeur - (1 / 3600 * maxi)).toFixed(2);


    date = new Date(0, 0, 0, dates[0], dates[1], dates[2] - 1);
    document.getElementById('temps').innerHTML = date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds();

    affichage = ((1 - (valeur - mini) / (maxi - mini)) * 74).toFixed(2);

    document.getElementById('circle-container__progress').style.strokeDashoffset = affichage;
  }

}, 1000);