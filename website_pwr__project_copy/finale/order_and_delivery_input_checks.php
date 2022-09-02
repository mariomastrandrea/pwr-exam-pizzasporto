<?php 

   $input_error_occurred = false;
   $error_messages = array();

   /* mi accerto che le variabili di sessione relative all'ordine esistano, e che ci sia almeno una pizza nel carrello */

   if(!isset($_SESSION["pizzas-shopping-cart"]) || 
      !isset($_SESSION["total-price"]) ||
      count($_SESSION["pizzas-shopping-cart"]) === 0) {
      
      $input_error_occurred = true;
      array_push($error_messages, "Errore: nessuna pizza rilevata o prezzo totale non rilevato. 
         Variabili di sessione non rilevate");
      return;
   }
   
   /* non ricontrollo il contenuto delle variabili di sessione, fidandomi del fatto che siano quelle da me impostate */

   // delivery address and delivery hour checks
   
   $delivery_address = trim($_POST["deliveryAddress"] ?? "");  /* per 'null coalescing operator', vedi "../AAA_info_per_i_docenti.txt" 4) */
   $delivery_hour = trim($_POST["deliveryHour"] ?? "");

   require "../check_utilities.php";

   /* imposto da codice il timezone, riferendolo all'orario italiano, e supponendo quindi che tutti gli 
      orari facciano riferimento al territorio italiano. Il server PHP installato sul Cloud presenta
      infatti un orario riferito ad UTC, ma non avendo accesso al file di configurazione php.ini,
      l'unico modo è modificarlo tramite codice in questo modo */
   date_default_timezone_set("Europe/Rome");

   $is_delivery_address_ok = check_address($delivery_address, $error_messages);
   $is_delivery_hour_ok = check_delivery_hour($delivery_hour, $error_messages);

   $input_error_occurred = count($error_messages) > 0;
?>