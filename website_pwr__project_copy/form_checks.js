"use strict";
//JS general validation methods

//this method receive an arbitrary amount of input IDs, but the last argument must be the general error box ID
function checkInputForm() { 
   
   const numArgs = arguments.length;
   if(numArgs === 0) return;

   //distinguish between inputs' id and generalErrorBox's id
   const generalErrorBoxId = arguments[numArgs - 1];        
   const inputIds = Array.from(arguments).slice(0, - 1);    /* creo un array contenente tutti i parametri tranne l'ultimo (tutti gli input IDs) */
                                                            /* il metodo statico Array.from() crea un array a partire da un oggetto iterabile. E' quindi utile per usufruire dei metodi della classe Array su una lista generica */
   /* per 'let', vedi "../AAA_info_per_i_docenti.txt", 9) */
   let areAllInputsCorrect = true;  

   for(let inputId of inputIds) {
      const isInputCorrect = checkFieldOnChange(inputId, generalErrorBoxId, true);  //check each input
      areAllInputsCorrect &&= isInputCorrect;
   }

   document.getElementById(generalErrorBoxId).innerText = "";  /* svuoto il box degli errori */
   return areAllInputsCorrect;
}

// the corresponding input error box, must have ID = {fieldName}-input-error-box
function checkFieldOnInput(inputId, generalErrorBoxId) {

   const inputElement = document.getElementById(inputId);
   /* per interpolazione, vedi "../AAA_info_per_i_docenti.txt", 8) */
   const inputErrorElement = document.getElementById(`${inputId}-input-error-box`); 
   
   if(inputErrorElement.innerText) {   // if some error is already present, continue to show new errors
      const result = checkFieldOnChange(inputId, generalErrorBoxId);
      inputElement.classList.remove("error");
      return result;
   }

   const generalErrorBoxElement = document.getElementById(generalErrorBoxId);

   /* per 'let', vedi "../AAA_info_per_i_docenti.txt", 9) */
   let generalErrors = [];
   const isInputOk = internalCheckIfIsInputOk(inputId, inputElement, [], generalErrors);

   inputElement.classList.remove("error");

   if(isInputOk) {
      inputElement.classList.add("ok");
      inputErrorElement.innerText = "";
      generalErrorBoxElement.innerText = "";
      return true;
   }
   
   generalErrorBoxElement.innerText = generalErrors.join("\n");
   inputElement.classList.remove("ok");
   return false;
}

// the corresponding input error box, must have ID = {fieldName}-input-error-box; 
// 'fromSubmit' is a flag indicating if this function has been called during a submit
function checkFieldOnChange(inputId, generalErrorBoxId, fromSubmit) {

   const inputElement = document.getElementById(inputId);
   /* per interpolazione, vedi "../AAA_info_per_i_docenti.txt", 8) */
   const inputErrorElement = document.getElementById(`${inputId}-input-error-box`);
   const generalErrorBoxElement = document.getElementById(generalErrorBoxId);

   /* uso degli array per contenere gli errori, poiché in JS vengono passati by reference */
   let inputErrors = [];
   let generalErrors = [];

   let isInputOk = internalCheckIfIsInputOk(inputId, inputElement, inputErrors, generalErrors);

   /* nel caso di onsubmit, controllo: 
   - se la nuova quantità inserita è la stessa di quella precedente;
   - se il nome della nuova pizza era già nel menù soprastante;
   in tal caso segnalo un errore 
   Nota: questi controlli sono effettuati anche lato server PHP, qui sono effettuati anche lato client per eccesso di zelo */
   if(isInputOk && fromSubmit) {
      //check if the new qty is the same as the previous one
      if(/^pizza-([\d]+)-qty$/.test(inputId) && inputElement.value.trim() === inputElement.defaultValue.trim()) {
         inputErrors.push(`Questa quantità era già ${inputElement.defaultValue.trim()}! Inserisci un nuovo valore`)
         isInputOk = false;
      }
      else if(inputId === "pizza-name") {
         const newPizzaName = inputElement.value.trim();

         //retrieve all pizza names
         const allPizzaNamesElements = Array.from(document.querySelectorAll("#menu-pizzas-table td:first-child")); /* creo un array dalla NodeList, per usufruire dei metodi della classe Array (es. map)*/
         const allPizzaNames = allPizzaNamesElements.map(element => element.innerText.trim()); /* array con i nomi di tutte le pizze */

         if(allPizzaNames.includes(newPizzaName)) {
            inputErrors.push(`Esiste già nel menù la pizza "${newPizzaName}"! Scegli un altro nome`);
            isInputOk = false;
         }
      }
   } 
      
   //show input error messages 
   if(!isInputOk) {
      inputErrorElement.innerText = inputErrors.join("\n");
      generalErrorBoxElement.innerText = generalErrors.join("\n");
      inputElement.classList.remove("ok");
      inputElement.classList.add("error");
      return false;
   }

   //else  (all ok)
   inputErrorElement.innerText = "";
   generalErrorBoxElement.innerText = "";
   inputElement.classList.add("ok");
   inputElement.classList.remove("error");
   
   return true;
}

function internalCheckIfIsInputOk(inputId, inputElement, inputErrors, generalErrors) {

   let isInputOk = false;

   switch(inputId) {

      case "name": 
         isInputOk = checkNameOrSurname(inputElement.value, "Nome", 2, 25, inputErrors, generalErrors);
         break;
      
      case "surname":
         isInputOk = checkNameOrSurname(inputElement.value, "Cognome", 2, 30, inputErrors, generalErrors);
         break;

      case "birthdate":
         isInputOk = checkBirthdate(inputElement.value, inputErrors, generalErrors);
         break;
      
      case "address":
      case "deliveryAddress":
         isInputOk = checkAddress(inputElement.value, inputErrors, generalErrors);
         break;

      case "money":
         isInputOk = checkMoney(inputElement.value, inputErrors, generalErrors);
         break;
      
      case "username":
      case "nick":
         isInputOk = checkNick(inputElement.value, inputErrors, generalErrors);
         break;
      
      case "password":
         isInputOk = checkPassword(inputElement.value, inputErrors, generalErrors);
         break;
      
      case "pizza-name":
         isInputOk = checkPizzaName(inputElement.value, inputErrors, generalErrors);
         break;

      case "pizza-ingredients":
         isInputOk = checkPizzaIngredients(inputElement.value, inputErrors, generalErrors);
         break;

      case "pizza-price":
         isInputOk = checkPizzaPrice(inputElement.value, inputErrors, generalErrors);
         break;
      
      case "pizza-qty":
         isInputOk = checkPizzaQty(inputElement.value, inputErrors, generalErrors);
         break;
      
      case "deliveryHour":
         isInputOk = checkDeliveryHour(inputElement.value, inputErrors, generalErrors);
         break;

      //add here new cases

      default:
         if(!/^pizza-([\d]+)-qty$/.test(inputId)) break;
         //here inputId is a pizza id
         isInputOk = checkPizzaQty(inputElement.value, inputErrors, generalErrors);         
         break;
   }

   return isInputOk;
}

//this method receive an arbitrary amount of input IDs, but the last argument must be the general error box ID
function resetFields() {

   const numArgs = arguments.length;

   if(numArgs === 0) return;

   /* per 'let', vedi "../AAA_info_per_i_docenti.txt", 9) */
   let count = 0;
   
   /* per ciascun elemento di input passato, tolgo la classe 'ok' (se presente) e svuoto il relativo box di errore */
   Array.from(arguments).forEach(id => {
      count += 1;
      const element = document.getElementById(id);

      if(count === numArgs) {
         //this is the last argument (generalErrorMessageBoxId)
         element.innerText = "";
         return;
      }

      element.classList.remove("ok");
      element.classList.remove("error");
      element.defaultValue = ""; /* così facendo, anche se ci sono valori di sessione salvati, i campi si svuoteranno; altrimenti non si svuoterebbero */
      
      /* per interpolazione, vedi "../AAA_info_per_i_docenti.txt", 8) */
      const errorInputElement = document.getElementById(`${id}-input-error-box`);
      errorInputElement.innerText = "";
   });
}

// * utility functions */

// es. to check 'user money' or 'pizza price'
function checkGeneralMoney(moneyInput, fieldName, inputErrors, generalErrors) {

   if(moneyInput)
      moneyInput = moneyInput.trim();

   /* per interpolazione, vedi "../AAA_info_per_i_docenti.txt", 8) */
   const generalErrorStatement = `Inserire un valore numerico positivo con 2 cifre decimali per il campo "${fieldName}", con '.' come separatore dei decimali`;

   if(!moneyInput) {
      inputErrors.push(`Il campo "${fieldName}" non può essere vuoto`);
      generalErrors.push(generalErrorStatement);
      return false;
   }

   /* per 'let', vedi "../AAA_info_per_i_docenti.txt", 9) */
   let notAllowedChars = moneyInput.match(/[^\d\.]/g);

   /* controllo se ci sono caratteri non ammessi */
   if(!checkNotAllowedChars(notAllowedChars, fieldName, inputErrors, generalErrorStatement, generalErrors))
      return false;

   if(!/^[\d]+\.[\d]{2}$/.test(moneyInput)) {
      inputErrors.push(`Il campo "${fieldName}" non è nel formato corretto`);
      generalErrors.push(generalErrorStatement);
      return false;
   }

   const moneyIntPart = moneyInput.split(".")[0];
   const intMoneyIntPart = Number(moneyIntPart);

   if(String(intMoneyIntPart) !== moneyIntPart) {
      inputErrors.push(`"${moneyInput}" non è un valore numerico valido per il campo "${fieldName}"`);
      generalErrors.push(generalErrorStatement);
      return false;
   }

   // money input is ok here
   return true;
}

// es. to check 'pizza name' or 'pizza ingredients'
function checkGeneralString(inputString, fieldName, maxLength, inputErrors, generalErrors) {

   if(inputString)
      inputString = inputString.trim();

   const generalErrorStatement = `Inserire un campo "${fieldName}" non vuoto, e che non superi i ${maxLength} caratteri`;

   if(!inputString) {
      inputErrors.push(`Il campo "${fieldName}" non può essere vuoto`);
      generalErrors.push(generalErrorStatement);
      return false;
   }

   if(inputString.length > maxLength) {
      inputErrors.push(`Il campo "${fieldName}" non può superare i ${maxLength} caratteri`);
      generalErrors.push(generalErrorStatement);
      return false;
   }

   if(/[ ]{2,}/.test(inputString)) {
      inputErrors.push(`Il campo "${fieldName}" non può contenere 2 spazi consecutivi`);
      generalErrors.push(generalErrorStatement);
      return false;
   }

   //input string ok here
   return true;
}

// function to check notAllowedChars array: 
// it returns 'true' if check goes well, 'false' otherwise
function checkNotAllowedChars(notAllowedChars, fieldName, inputErrors, generalErrorStatement, generalErrors) {
   
   if(notAllowedChars === null || notAllowedChars.length === 0) 
      return true; // no chars -> ok

   notAllowedChars = notAllowedChars.map(char => {

      if (/^[\s]$/.test(String(char))) return "(spazio)";
      return `'${char}'`;
   });

   /* per 'let', vedi "AAA_info_per_i_docenti.txt", 9) */
   let uniqueNotAllowedChars = [];

   notAllowedChars.forEach(char => {
      if(!uniqueNotAllowedChars.includes(char))
         uniqueNotAllowedChars.push(char);
   });

   uniqueNotAllowedChars = uniqueNotAllowedChars.join(", ");

   inputErrors.push(`Il campo "${fieldName}" contiene i seguenti caratteri non ammessi: ${uniqueNotAllowedChars}`);
   generalErrors.push(generalErrorStatement);
   return false;
}