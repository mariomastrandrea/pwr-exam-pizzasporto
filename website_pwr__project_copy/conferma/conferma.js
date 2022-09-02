"use strict";

function checkDeliveryHour(deliveryHourInput, inputErrors, generalErrors) {

   if(deliveryHourInput)
      deliveryHourInput = deliveryHourInput.trim();

   const generalErrorStatement = `Inserire nel campo "Orario di consegna" un orario valido in formato "HH:MM" almeno 45 minuti dopo l'orario attuale. Ricorda che la pizzeria effettua consegne solo nel giorno corrente, dalle ore 12:00 alle ore 23:59 (incluse)`;

   if(!deliveryHourInput) {
      /* Il tag <input type="time"> contiene una stringa vuota sia in caso di input assente che nel caso di formato 
         errato. Quindi non posso distinguere da una stringa vuota se si è trattato di un errore o di un assenza di input */
      inputErrors.push(`Il campo "Orario di consegna" è vuoto o ha un formato errato`);   
      generalErrors.push(generalErrorStatement);
      return false;
   }

   /* per 'let', vedi "../AAA_info_per_i_docenti.txt", 9) */
   let notAllowedChars = deliveryHourInput.match(/[^\d:]/g);

   /* controllo se ci sono caratteri non ammessi (anche se, secondo quanto detto sopra, difficilmente si ricadrà in questo caso) */
   if(!checkNotAllowedChars(notAllowedChars, "Orario di consegna", inputErrors, generalErrorStatement, generalErrors))
      return false;

   if(!/^[\d]{2}:[\d]{2}$/.test(deliveryHourInput)) {
      inputErrors.push(`Il campo "Orario di consegna" ha un formato errato`);   
      generalErrors.push(generalErrorStatement);
      return false;
   }

   const timeFields = deliveryHourInput.split(":");
   const intHours = Number(timeFields[0]);   // certainly an integer
   const intMinutes = Number(timeFields[1]); // certainly an integer

   if(intHours > 23 || intMinutes > 59) {
      inputErrors.push(`Il campo "Orario di consegna" non contiene un orario valido un orario valido`);
      generalErrors.push(generalErrorStatement);
      return false;
   }

   //hour and minutes format ok here

   // (1) check opening and closing hours (12:00 - 23:59)
   
   if(intHours < 12) {
      inputErrors.push(`L'orario inserito nel campo "Orario di consegna" non rientra nell'orario di attività della pizzeria`);
      generalErrors.push(generalErrorStatement);
      return false;
   }

   /* per i controlli sull'orario corrente, ipotizzo che il client si trovi nello stesso 
   territorio geografico del server, ovvero in Italia, e che quindi abbia lo stesso timezone.
   E' un'ipotesi forte, ma plausibile, trattandosi di una pizzeria locale.
   Ad ogni modo, il semplice oggetto Date di JavaScript non consente di gestire i Timezone, 
   per cui servirebbe un oggetto di una libreria esterna per effettuare il controllo opportunamente */

   // (2) check delivery hour on the current day
   const currentTime = new Date();
   const deliveryTime = new Date(currentTime.getFullYear(), currentTime.getMonth(), /* creo un oggetto Date che abbia anno, mese, giorno e secondi correnti, ma con ora e minuti definiti dall'utente */
      currentTime.getDate(), intHours, intMinutes, currentTime.getSeconds());

   if(deliveryTime.valueOf() < currentTime.valueOf()) {
      inputErrors.push(`L'orario inserito nel campo "Orario di consegna" è un orario precedente a quello corrente`);
      generalErrors.push(generalErrorStatement);
      return false;
   }

   // (3) check delivery hour after 45 minutes 
   const minimumWaitMinutes = 45;
   const minAllowedDeliveryTime = new Date(currentTime.getFullYear(), currentTime.getMonth(),   /* creo un oggetto Date che sia avanti di 45 minuti rispetto all'orario corrente */
                  currentTime.getDate(), currentTime.getHours(), 
                  currentTime.getMinutes() + minimumWaitMinutes, currentTime.getSeconds());

   if(deliveryTime.valueOf() < minAllowedDeliveryTime.valueOf()) {
      /* utilizzo un array di options con 2 parametri per formattare l'oggetto date nel formato HH:MM. Per il primo parametro passo un array vuoto, perché non voglio usare un altro timezone  */
      inputErrors.push(`L'orario inserito in "Orario di consegna" è troppo imminente. Deve essere almeno tra 45 minuti (primo orario disponibile: ${minAllowedDeliveryTime.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})})`);
      generalErrors.push(generalErrorStatement);
      return false;
   }

   //delivery hour ok here
   return true;
}

