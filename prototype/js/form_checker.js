

const password = document.getElementById("password"),
confirm_password = document.getElementById("confirm_password"),
tel = document.getElementById("tel") ;

function validatePassword(){
  if(password.value != confirm_password.value) {
    confirm_password.setCustomValidity("Les mots de passe sont différents");
  } else {
    confirm_password.setCustomValidity('');
  }
}
function validatePhoneNumber(){
	if(isNaN(tel.value)) {
	  tel.setCustomValidity("Le numéro de téléphone doit être uniquement composé de chiffres");
	}else if(tel.value.length!=10){
	  tel.setCustomValidity("Le numéro de téléphone doit être composé de 10 chiffres ("+tel.value.length+" actuellement)");

  } else {
	  tel.setCustomValidity('');
	}
  }
password.onchange = validatePassword;
confirm_password.onkeyup = validatePassword;
tel.onchange = validatePhoneNumber;
