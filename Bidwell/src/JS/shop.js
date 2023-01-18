  var modal = document.getElementById("myModal");
  var nbr; var money;
// Ouverture du popup
function affish(jeton, prix) {
  modal.style.display="flex";
  nbr = jeton;
  money= prix;
}

function conf(){
//Crée une nouvelle requête XMLHTTP à envoyer au serveur
  const xhttp = new XMLHttpRequest();

  //Lorsque la requête est "prête", indique que la division classe "annonce" prendre comme HTML résultat du serveur
  xhttp.onload = function() {
    modal.style.display ="none"
    document.getElementById("nombrejetons").innerHTML= this.responseText + " jetons";
  }

  //Ouvre la requête au serveur avec pour informations le nombre de jetons à donner
  xhttp.open("GET", "../Ajax/shop-ajax.php?valeur=" + nbr);

  //Envoie la requête au serveur
  xhttp.send();
}

// Fermeture de la page 
function stop() {
  modal.style.display = "none";
}


function initPayPalButton() {
  paypal.Buttons({
    style: {
      shape: 'pill',
      color: 'gold',
      layout: 'horizontal',
      label: 'checkout',
      
    },

    createOrder: function(data, actions) {
      return actions.order.create({
        purchase_units: [{"amount":{"currency_code":"EUR","value":money}}]
      });
    },

    onApprove: function(data, actions) {
      return actions.order.capture().then(function(orderData) {
        
        // Full available details
        console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));

        // Show a success message within this page, e.g.
        const element = document.getElementById('paypal-button-container');
        conf();

        // Or go to another URL:  actions.redirect('thank_you.html');
        
      });
    },

    onError: function(err) {
      console.log(err);
    }
  }).render('#paypal-button-container');
}
initPayPalButton();



