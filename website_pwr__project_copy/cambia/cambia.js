"use strict";

function checkPizzaQty(pizzaQtyInput, inputErrors, generalErrors) {
   
   if(pizzaQtyInput)
      pizzaQtyInput = pizzaQtyInput.trim();

   const generalErrorStatement = `Inserire un campo "Quantità" che sia un numero intero valido non negativo`;

   if(!pizzaQtyInput) {
      inputErrors.push(`Il campo "Quantità" non può essere vuoto`);
      generalErrors.push(generalErrorStatement);
      return false;
   }

   /* per 'let', vedi "../AAA_info_per_i_docenti.txt", 9) */
   let notAllowedChars = pizzaQtyInput.match(/[\D]/g);   /* match su caratteri non numerici */

   if(!checkNotAllowedChars(notAllowedChars, "Quantità", inputErrors, generalErrorStatement, generalErrors))
      return false;

   const intPizzaQty = Number(pizzaQtyInput); 

   /* utile a scremare il caso di zeri iniziali */
   if(String(intPizzaQty) !== pizzaQtyInput) {
      inputErrors.push(`La quantità "${pizzaQtyInput}" non è un numero intero valido`);
      generalErrors.push(generalErrorStatement);
      return false;
   }

   //pizza qty ok here
   return true;
}

/* il campo "nome", nella tabella `pizze` di MySQL, è di tipo VARCHAR(32)  */
function checkPizzaName(pizzaNameInput, inputErrors, generalErrors) {
   return checkGeneralString(pizzaNameInput, "Nome", 32, inputErrors, generalErrors);
}

/* il campo "ingredienti", nella tabella `pizze` di MySQL, è di tipo VARCHAR(60)  */
function checkPizzaIngredients(pizzaIngredientsInput, inputErrors, generalErrors) {
   return checkGeneralString(pizzaIngredientsInput, "Ingredienti", 60, inputErrors, generalErrors);
}

function checkPizzaPrice(pizzaPriceInput, inputErrors, generalErrors) {
   return checkGeneralMoney(pizzaPriceInput, "Prezzo", inputErrors, generalErrors);
}
