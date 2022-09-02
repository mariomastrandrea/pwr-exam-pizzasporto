"use strict";


// first argument is a box id containing all the select tags inside a .pizza-qty
function checkAllQuantitiesZerosIn(quantitiesInputsBoxId, generalErrorMessagesBoxId) {

   const generalErrorMessagesBox = document.getElementById(generalErrorMessagesBoxId);
   const quantitiesInputsBox = document.getElementById(quantitiesInputsBoxId);
   /* vado a prendere tutti gli elementi di input delle quantità */
   const qtySelectTags = quantitiesInputsBox.querySelectorAll(".pizza-qty select");
   const qtySelectTagsArray = Array.from(qtySelectTags);

   for(let qtyElement of qtySelectTagsArray) {
      const qtyInput = qtyElement.value;
      const isValidInt = checkPizzaQty(qtyInput, [], []);

      if(isValidInt && Number(qtyInput) > 0) return true; //it exists almost one valid int > 0 
   }

   //all qty are zeros or not valid int -> error
   qtySelectTagsArray.forEach(selectTag => selectTag.classList.add("error"));
   generalErrorMessagesBox.innerText = "Impossibile effettuare un ordine vuoto. Seleziona almeno una pizza!";
   return false;
}

// first argument is a box id containing all the select tags inside a .pizza-qty
function resetAllQuantitiesFieldsIn(quantitiesInputsBoxId, generalErrorMessagesBoxId) {

   const generalErrorMessagesBox = document.getElementById(generalErrorMessagesBoxId);
   const quantitiesInputsBox = document.getElementById(quantitiesInputsBoxId);
   /* vado a prendere tutti gli elementi di input delle quantità */
   const qtySelectTags = quantitiesInputsBox.querySelectorAll(".pizza-qty select");
   const qtySelectTagsArray = Array.from(qtySelectTags);

   qtySelectTagsArray.forEach(selectTag => selectTag.classList.remove("error"));
   generalErrorMessagesBox.innerText = "";
}

function checkAllPizzasQuantitiesOnChange(qtyInputId, quantitiesInputsBoxId, generalErrorMessagesBoxId) {

   const qtyInputElement = document.getElementById(qtyInputId);

   if(!qtyInputElement.classList.contains("error")) return; //it's not necessary to continue

   const qtyInput = qtyInputElement.value;

   if(checkPizzaQty(qtyInput, [], []) && Number(qtyInput) > 0) 
      resetAllQuantitiesFieldsIn(quantitiesInputsBoxId, generalErrorMessagesBoxId);

   /* in sintesi: se il campo di input corrente ha la classe CSS 'error', e se la quantità appena inserita 
    è un intero valido > 0, allora resetto tutti i campi togliendo la classe CSS 'error' e svuotando il box degli errori */
}