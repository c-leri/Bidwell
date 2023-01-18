function twoDigits(num) {
  return `${num}`.length < 2 ? `0${num}` : num;
}

function getRatioTempsActuel(dateDebut, instantFin, instantDerniereEnchere) {
  let maintenant = new Date();
  if (maintenant > dateDebut) {
      let differenceMaintenantFin = instantFin - maintenant;
      return differenceMaintenantFin / (instantFin - instantDerniereEnchere);
  } else {
      return 1;
  }
}

window.setInterval(function () {
  let mini = parseFloat(document.getElementById('min').innerHTML);
  let maxi = parseFloat(document.getElementById('max').innerHTML);
  let date = document.getElementById('temps').innerText;
  let dates = document.getElementById('temps').innerText.split(":");
  let instantDerniereEnchere = new Date(parseInt(document.getElementById('instantDerniereEnchere').value) * 1000);
  let instantFin = new Date(parseInt(document.getElementById('instantFin').value) * 1000);
  let dateDebut = new Date(parseInt(document.getElementById('dateDebut').value) * 1000);

  const valeur = mini + getRatioTempsActuel(dateDebut, instantFin, instantDerniereEnchere) * (maxi - mini);

  if (date.includes("00:00:00")) {
    location.reload();
  } else {
    document.getElementById('temps').innerHTML = `${twoDigits(dates[0])}:${twoDigits(dates[1])}:${twoDigits(dates[2] - 1)}`;

    if (mini < valeur) {
      document.getElementById('act').innerHTML = valeur.toFixed(2);

      affichage = (74 - ((valeur-mini)/(maxi-mini)) * 74).toFixed(2);

      document.getElementById('circle-container__progress').style.setProperty('stroke-dashoffset', affichage);
    }
  }

}, 1000);
