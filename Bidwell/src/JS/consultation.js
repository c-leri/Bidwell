function dateToSeconds(date) {
  return (date.getHours() * 60 + date.getMinutes()) * 60 + date.getSeconds()
}

function getRatioTempsActuel(dateDebut, instantFin, instantDerniereEnchere) {
  let maintenant = new Date();
  // console.log(`maintenant: ${maintenant}\ndateDebut: ${dateDebut}\ninstantFin: ${instantFin}\ninstantDerniereEnchere: ${instantDerniereEnchere}`);
  if (maintenant > dateDebut) {
      let differenceMaintenantFin = instantFin - maintenant;
      // console.log(`(instantFin - maintenant): ${instantFin - maintenant}\n(differenceMaintenantFin / instantFin - instantDerniereEnchere): ${differenceMaintenantFin / instantFin - instantDerniereEnchere}`)
      return differenceMaintenantFin / (instantFin - instantDerniereEnchere);
  } else {
      return 1;
  }
}

window.setInterval(function () {
  valeur = parseFloat(document.getElementById('act').innerHTML);
  mini = parseFloat(document.getElementById('min').innerHTML);
  maxi = parseFloat(document.getElementById('max').innerHTML);
  titre = document.getElementById('dateTitle').innerText;
  date = document.getElementById('temps').innerText;
  dates = document.getElementById('temps').innerText.split(":");
  instantDerniereEnchere = new Date(parseInt(document.getElementById('instantDerniereEnchere').value) * 1000);
  instantFin = new Date(parseInt(document.getElementById('instantFin').value) * 1000);
  dateDebut = new Date(parseInt(document.getElementById('dateDebut').value) * 1000);

  valeur = mini + getRatioTempsActuel(dateDebut, instantFin, instantDerniereEnchere) * (maxi - mini);

  // console.log(`valeur: ${valeur}\nmini: ${mini}\ngetRatiotempsActuel(): ${getRatioTempsActuel(dateDebut, instantFin, instantDerniereEnchere)}\n(maxi - mini): ${(maxi - mini)}`);

  if (date.includes("0:0:0") || date.includes("00:00:00")) {
    location.reload();
  } else if (titre.includes("commencera ")) {
    
    date = new Date(0, 0, 0, dates[0], dates[1], dates[2] - 1);
    document.getElementById('temps').innerHTML = date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds();

  } else if (mini < valeur) {
    date = new Date(0, 0, 0, dates[0], dates[1], dates[2] - 1);
    document.getElementById('temps').innerHTML = date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds();

    tpsRestant = dateToSeconds(date);
    document.getElementById('act').innerHTML = valeur.toFixed(2);

    affichage = (74 - ((valeur-mini)/(maxi-mini)) * 74).toFixed(2);
    console.log(affichage);

    document.getElementById('circle-container__progress').setAttribute('style', `stroke-dashoffset:${affichage}`);
  }

}, 1000);
