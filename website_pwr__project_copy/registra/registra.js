"use strict";

/* uso degli array per contenere gli errori, poiché in JS vengono passati by reference */

function checkNameOrSurname(nameInput, fieldName, minLength, maxLength, inputErrors, generalErrors) {

   if(nameInput)
      nameInput = nameInput.trim();

   /* uso interpolazione, anziché concatenazione (vedi "../info_per_i_docenti.txt", 7) */
   const generalErrorStatement = `Inserire un campo "${fieldName}" che sia lungo almeno ${minLength} caratteri, ma non più di ${maxLength} caratteri, e che contenga solo lettere o spazi(1 per volta)`;

   if(!nameInput) {
      inputErrors.push(`Il campo "${fieldName}" non può essere vuoto`);
      generalErrors.push(generalErrorStatement);
      return false;
   }

   if(nameInput.length < minLength) {
      inputErrors.push(`Il campo "${fieldName}" è troppo corto (minimo ${minLength} caratteri)`);
      generalErrors.push(generalErrorStatement);
      return false;
   }

   if(nameInput.length > maxLength) {
      inputErrors.push(`Il campo "${fieldName}" è troppo lungo (massimo ${maxLength} caratteri)`);
      generalErrors.push(generalErrorStatement);
      return false;
   }

   /* per 'let', vedi "../AAA_info_per_i_docenti.txt", 9) */
   let notAllowedChars = nameInput.match(/[^a-zA-Z ]/g);

   /* controllo se ci sono caratteri non ammessi */
   if(!checkNotAllowedChars(notAllowedChars, fieldName, inputErrors, generalErrorStatement, generalErrors))
      return false;

   if(/[ ]{2,}/.test(nameInput)) {
      inputErrors.push(`Il campo "${fieldName}" contiene una sequenza di spazi troppo lunga`);
      generalErrors.push(generalErrorStatement);
      return false;
   }

   // name or surname ok here
   return true;
}

function checkBirthdate(birthdateInput, inputErrors, generalErrors) {

   if(birthdateInput)
      birthdateInput = birthdateInput.trim();

   /* per interpolazione, vedi "../AAA_info_per_i_docenti.txt", 8) */
   const generalErrorStatement = `Inserire una data di nascita valida nel formato 'aaaa-mm-gg' ('0' come prima cifra può essere omesso)`;

   if(!birthdateInput) {
      inputErrors.push(`Il campo "Data di nascita" non può essere vuoto`);
      generalErrors.push(generalErrorStatement);
      return false;
   }

   /* per 'let', vedi "../AAA_info_per_i_docenti.txt", 9) */
   let notAllowedChars = birthdateInput.match(/[^\d\-]/g);

   /* controllo se ci sono caratteri non ammessi */
   if(!checkNotAllowedChars(notAllowedChars, "Data di nascita", inputErrors, generalErrorStatement, generalErrors))
      return false;

   if(!/^[\d]{4}-[\d]{1,2}-[\d]{1,2}$/.test(birthdateInput)) {
      inputErrors.push(`Il campo "Data di nascita" non è nel formato corretto`);
      generalErrors.push(generalErrorStatement);
      return false;
   }

   const dateFields = birthdateInput.split("-");

   const year = Number(dateFields[0]);
   const month = Number(dateFields[1]) - 1;
   const dayOfMonth = Number(dateFields[2]);

   const date = new Date(year, month, dayOfMonth);

   if (!date || date.getFullYear() !== year || date.getMonth() !== month || date.getDate() !== dayOfMonth) {
      //it's not a valid date
      inputErrors.push("La data inserita non è valida");
      generalErrors.push(generalErrorStatement);
      return false;
   }

   if(date.valueOf() >= new Date().valueOf()) {
      inputErrors.push("La data di nascita deve essere nel passato");
      generalErrors.push(generalErrorStatement);
      return false;
   }

   //birthdate ok here
   return true;
}

function checkAddress(addressInput, inputErrors, generalErrors) {

   if(addressInput)
      addressInput = addressInput.trim();

   /* per interpolazione, vedi "../AAA_info_per_i_docenti.txt", 8) */
   const generalErrorStatement = `Inserire un indirizzo di domicilio del tipo\n"(Via/Corso/Largo/Piazza/Vicolo) nome numeroCivico", con 'nome' contenente solo lettere e spazi(1 per volta), e 'numeroCivico' numero intero valido da 1 a 4 cifre`;
   
   if(!addressInput) {
      inputErrors.push(`Il campo "Domicilio" non può essere vuoto`);
      generalErrors.push(generalErrorStatement);
      return false;
   }

   if(!/^(Via|Corso|Largo|Piazza|Vicolo) [a-zA-Z ]+ [\d]+$/.test(addressInput)) {
      inputErrors.push(`Il campo "Domicilio" non è nel formato corretto`);
      generalErrors.push(generalErrorStatement);
      return false;
   }

   if(/[ ]{2,}/.test(addressInput)) {
      inputErrors.push(`Il campo "Domicilio" non può contenere 2 spazi consecutivi`);
      generalErrors.push(generalErrorStatement);
      return false;
   }

   const addressFields = addressInput.split(" ");
   const addressNumber = addressFields[addressFields.length - 1];

   if(addressNumber.length > 4) {
      inputErrors.push(`Il 'numero civico' del campo "Domicilio" non può contenere più di 4 cifre`);
      generalErrors.push(generalErrorStatement);
      return false;
   }

   const intAddressNumber = Number(addressNumber); 

   /* utile a scremare il caso di zeri iniziali */
   if(String(intAddressNumber) !== addressNumber) {
      inputErrors.push(`Il 'numero civico' del campo "Domicilio" non è un numero intero valido`);
      generalErrors.push(generalErrorStatement);
      return false;
   }

   //address ok here
   return true;
}

function checkMoney(moneyInput, inputErrors, generalErrors) {
   return checkGeneralMoney(moneyInput, "Credito", inputErrors, generalErrors);
}

function checkNick(nickInput, inputErrors, generalErrors) {

   if(nickInput)
      nickInput = nickInput.trim();

   /* per interpolazione, vedi "../AAA_info_per_i_docenti.txt", 8) */
   const generalErrorStatement = `Inserire un campo "Username" che sia lungo da 3 a 8 caratteri, che contenga solamente numeri(0-9), lettere(a-z,A-Z), '-' e '_', e che cominci con un carattere alfabetico`;

   if(!nickInput) {
      inputErrors.push(`Il campo "Username" non può essere vuoto`);
      generalErrors.push(generalErrorStatement);
      return false;
   }

   if(nickInput.length < 3) {
      inputErrors.push(`Il campo "Username" è troppo corto (minimo 3 caratteri)`);
      generalErrors.push(generalErrorStatement);
      return false;
   }

   if(nickInput.length > 8) {
      inputErrors.push(`Il campo "Username" è troppo lungo (massimo 8 caratteri)`);
      generalErrors.push(generalErrorStatement);
      return false;
   }

   /* per 'let', vedi "../AAA_info_per_i_docenti.txt", 9) */
   let notAllowedChars = nickInput.match(/[^\w\-]/g);

   /* controllo se ci sono caratteri non ammessi */
   if(!checkNotAllowedChars(notAllowedChars, "Username", inputErrors, generalErrorStatement, generalErrors))
      return false;

   const firstChar = nickInput.charAt(0);

   if(!/^[a-zA-Z]$/.test(firstChar)) {
      inputErrors.push(`Il campo "Username" deve cominciare con un carattere alfabetico`);
      generalErrors.push(generalErrorStatement);
      return false;
   }

   //nick is ok here
   return true;
}

function checkPassword(passwordInput, inputErrors, generalErrors) {

   if(passwordInput)
      passwordInput = passwordInput.trim();

   /* per interpolazione, vedi "../AAA_info_per_i_docenti.txt", 8) */   
   const generalErrorStatement = `Inserire un campo "Password" che sia lungo da 6 a 12 caratteri, che contenga solamente numeri(0-9), lettere(a-z,A-Z) e segni di interpunzione, e che contenga almeno: 1 lettera minuscola, 1 lettera maiuscola, 2 numeri e 2 segni di interpunzione`;

   if(!passwordInput) {
      inputErrors.push(`Il campo "Password" non può essere vuoto`);
      generalErrors.push(generalErrorStatement);
      return false;
   }

   if(passwordInput.length < 6) {
      inputErrors.push(`Il campo "Password" è troppo corto (minimo 6 caratteri)`);
      generalErrors.push(generalErrorStatement);
      return false;
   }

   if(passwordInput.length > 12) {
      inputErrors.push(`Il campo "Password" è troppo lungo (massimo 12 caratteri)`);
      generalErrors.push(generalErrorStatement);
      return false;
   }

   /* JS non supporta le POSIX classes, dunque la corrispondente classe 
   POSIX [:punct:] è stata sostituita dalla sequenza: !"\#$%&'()*+,\-./:;<=>?@\[\\\]^_‘{|}~ 
   fonte: http://www.regular-expressions.info/posixbrackets.html */
   let notAllowedChars = passwordInput.match(/[^0-9a-zA-Z!"\#$%&'()*+,\-./:;<=>?@\[\\\]^_‘{|}~]/g);

   /* controllo se ci sono caratteri non ammessi */
   if(!checkNotAllowedChars(notAllowedChars, "Password", inputErrors, generalErrorStatement, generalErrors))
      return false;

   if(!/[a-z]/.test(passwordInput)) {
      inputErrors.push(`Il campo "Password" deve contenere almeno una lettera minuscola`);
      generalErrors.push(generalErrorStatement);
      return false;
   }

   if(!/[A-Z]/.test(passwordInput)) {
      inputErrors.push(`Il campo "Password" deve contenere almeno una lettera maiuscola`);
      generalErrors.push(generalErrorStatement);
      return false;
   }

   const onlyDigitsPassword = passwordInput.replace(/[\D]/g, "");

   if(onlyDigitsPassword.length < 2) {
      inputErrors.push(`Il campo "Password" deve contenere almeno 2 cifre numeriche`);
      generalErrors.push(generalErrorStatement);
      return false;
   }

   const onlyPunctuationPassword = passwordInput.replace(/[^!"\#$%&'()*+,\-./:;<=>?@\[\\\]^_‘{|}~]/g, "");

   if(onlyPunctuationPassword.length < 2) {
      inputErrors.push(`Il campo "Password" deve contenere almeno 2 caratteri di interpunzione`);
      generalErrors.push(generalErrorStatement);
      return false;
   }

   //password is ok here
   return true;
}