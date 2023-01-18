  var modal = document.getElementById("myModal");
  var nbr; var money;
// Ouverture du popup
function affish(jeton, prix) {
  modal.style.display="flex";
  nbr = jeton;
  money= prix;
}

function backstep(){
  document.getElementById('lescond').focus();
}

function checking(){
  console.log(document.getElementById('conscience').checked);
  if (document.getElementById('conscience').checked) {
    document.getElementById('couvrebouton').style.display = 'none';
  } else {
    document.getElementById('couvrebouton').style.display = 'block';
  }
}



//Fonction actualisant la solde en jetons de l'utilisateur et fermant le popup une fois la transaction terminée
function conf(){
//Crée une nouvelle requête XMLHTTP à envoyer au serveur
  const xhttp = new XMLHttpRequest();

  //Lorsque la requête est "prête", indique que la division classe "annonce" prendre comme HTML résultat du serveur
  xhttp.onload = function() {
    modal.style.display ="none"
    document.getElementById("nombrejetons").innerHTML= this.responseText + " jetons";
  }

  //Ouvre la requête au serveur avec pour informations le nombre de jetons à donner
  xhttp.open("GET", "shopAchat.ctrl.php?valeur=" + nbr);

  //Envoie la requête au serveur
  xhttp.send();
}

// Fermeture de la page 
function stop() {
  modal.style.display = "none";
}


//Paypal, je n'ai pas compris tout le fonctionnement j'ai principalement accepté que cela fonctionne
function initPayPalButton() {
  paypal.Buttons({
    style: {
      shape: 'pill',
      color: 'gold',
      layout: 'horizontal',
      label: 'checkout',
    },
    // onInit is called when the button first renders
    onInit: function(data, actions) {

      // Disable the buttons
      actions.disable();

      // Listen for changes to the checkbox
      document.querySelector('#conscience')
        .addEventListener('change', function(event) {

          // Enable or disable the button when it is checked or unchecked
          if (event.target.checked) {
            actions.enable();
          } else {
            actions.disable();
          }
        });
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



