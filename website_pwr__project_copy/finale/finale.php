<?php 
   require "../pages_utilities.php";
   require "../session_code.php";

   if($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["logout"]) && $_GET["logout"] === "" &&
      $active_session && $is_user_authenticated) {
      /* do all'utente la possibilità di fare il LOGOUT anche da questa pagina(con una GET request), 
         che solitamente è acceduta in POST, mediante redirect alla pagina LOGIN */
      header("Location: ../login/login.php?logout");
      return;
   }

   /* questa pagina deve essere acceduta solo in POST, tramite il form della pagina CONFERMA */
   if($_SERVER["REQUEST_METHOD"] !== "POST") {  
      print_bad_request();
      return;
   }

   //session are required to access this page
   if(!$active_session) {
      print_sessions_required();
      return;
   }
   
   //unset useless SESSION variables
   unset($_SESSION["sign-up-info"]);
   unset($_SESSION["updated-pizza"]);

   /* ovviamente non elimino qui le variabili di sessione dell'ordine, utili 
      ad effettuare proprio l'ordine stesso in questa pagina */
   
   if(!$is_user_authenticated) {
      //user must be authenticated to access this page
      print_unauthorized();
      return;
   }

   require "order_and_delivery_input_checks.php";

   if(!$input_error_occurred)
      require "process_order.php";
   else
      http_response_code(400);  //client error -> bad request
?> 

<!DOCTYPE html>
<html lang="it">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="author" content="Mario Mastrandrea - matricola 256226">
   <meta name="description" content="Sito esame PWR - Pagina FINALE - Pagina di esecuzione effettiva 
      dell'ordine e di comunicazione sull'esito dell'ordine stesso. La pagina contiene una segnalazione
      di errore, qualora ci fossero stati problemi nell'esecuzione dell'ordine, oppure un messaggio
      con l'esito positivo dell'ordine e informazioni sull'ordine stesso">
   <meta name="keywords" content="finale, ordine, conferma, pizze, quantit&agrave;, 
      totale, indirizzo, ora, orario, consegna">
   <title>Pagina FINALE - <?= $input_error_occurred ? "Errore di processamento dell'ordine" : 
      "Ordine effettuato con successo" ?></title>
   <link rel="icon" href="../images/icons/<?= $input_error_occurred ? "error" : "delivery" ?>_icon.png">
   <link rel="stylesheet" href="../styles.css">
   <link rel="stylesheet" href="finale.css">
</head>

<body>
   <?= print_header_and_select("finale", $is_user_authenticated, $is_user_manager) ?>

   <div class="main-container">
    
     <?php 
         if($input_error_occurred) {  ?> 
            <h1>Si &egrave; verificato un errore <img src="../images/red_error.png" alt="red error icon" class="inline"></h1> <?php
         }
         else { ?>
            <h1>Il tuo ordine &egrave; andato a buon fine!
               <img src="../images/delivery.png" alt="delivery icon" class="inline">
            </h1> <?php 
         } 
      ?>
         
      <main id="order-info-box" class="main-box<?= $input_error_occurred ? " error-wrapper" : "" ?>">
         <?php 
            require "order_info_content.php";
         ?>
      </main>
      
   </div> 
   
   <?= print_footer_of_page("finale") ?>
</body>

</html>