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

   /* questa pagina deve essere acceduta solo in POST, tramite il form della pagina ORDINA */
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

   /* elimino per scrupolo anche qui le variabili di sessione sulle pizze da ordinare,
      infatti verranno ri-settate dopo (se non ci saranno errori) */

   // unset shopping_cart SESSION variables
   if(isset($_SESSION["pizzas-shopping-cart"]))
      foreach(array_keys($_SESSION["pizzas-shopping-cart"]) as $key)
         unset($_SESSION["pizzas-shopping-cart"][$key]);
      
   unset($_SESSION["pizzas-shopping-cart"]);
   unset($_SESSION["total-price"]); 
   
   if(!$is_user_authenticated) {
      //user must be authenticated to access this page
      print_unauthorized();
      return;
   }

   require "../info/retrieve_available_pizzas.php";   /* prendo dal DB tutte le pizza ordinabili: riutilizzo questo file della pagina INFO */
   require "order_input_checks.php";
   
   if($error_occurred && !$no_available_pizzas) http_response_code(500); //error retrieving data from db -> internal server error
   if($input_error_occurred) http_response_code(400);  //client error -> bad request
?> 

<!DOCTYPE html>
<html lang="it">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="author" content="Mario Mastrandrea - matricola 256226">
   <meta name="description" content="Sito esame PWR - Pagina CONFERMA - Pagina di riepilogo dell'ordine,
      i cui dati sono stati inseriti nel form della pagina ORDINA. Contiene un elenco delle pizze ordinate, 
      con nome, quantità, prezzo unitario e prezzi complessivi, un campo per l'inserimento dell'indirizzo di
      consegna (precompilato con l'indirizzo di domicilio dell'utente) e un campo per l'inserimento dell'ora
      di consegna desiderata. L'indirizzo deve essere nello stesso formato di quello dell'utente, mentre l'ora 
      consegna deve essere nel giorno corrente (quindi successiva a quella corrente) e minimo 45 minuti dopo
      l'orario dell'ordine. Si considera che la pizzeria faccia consegne dalle 12:00 alle 24:00">
   <meta name="keywords" content="conferma, ordine, pizze, quantit&agrave;, nome, prezzo, 
      subtotale, totale, indirizzo, ora, orario, consegna">
   <title>Pagina CONFERMA - riepilogo dell'ordine</title>
   <link rel="icon" href="../images/icons/<?= ($error_occurred || $input_error_occurred) ? 
      "error" : "order_recap" ?>_icon.png">
   <link rel="stylesheet" href="../styles.css">
   <link rel="stylesheet" href="conferma.css">
   <script src="../form_checks.js"></script>   
   <script src="../registra/registra.js"></script> <!-- useful for checkAddress() function -->
   <script src="conferma.js"></script>          
</head>

<body>
   <?= print_header_and_select("conferma", $is_user_authenticated, $is_user_manager) ?>

   <div class="main-container">
    
     <?php 
         if($error_occurred || $input_error_occurred) {  ?> 
            <h1>Si &egrave; verificato un errore <img src="../images/red_error.png" alt="red error icon" class="inline"></h1> <?php
         }
         else { ?>
            <h1>Riepilogo dell'ordine
               <img src="../images/icons/order_recap_icon.png" alt="order recap icon" class="inline">
            </h1> 
            <h2>Utente - <span class="user-name">
                  <?= "{$_SESSION["logged-in-user"]["name"]} {$_SESSION["logged-in-user"]["surname"]}" ?></span>
            </h2>  <?php 
         } 
      ?>

      <main>
         
         <div id="order-recap-box" class="main-box<?= $input_error_occurred ? " error-wrapper" : "" ?>">
            <?php 
               require "order_recap_content.php";
            ?>
         </div>

         <?php 
            if(!$error_occurred && !$input_error_occurred) { 
               require "delivery_info_content.php";   
            }
         ?>

      </main>
      
   </div> 
   
   <?= print_footer_of_page("conferma") ?>
</body>

</html>