<?php 
   require "../pages_utilities.php";
   require "../session_code.php";

    /* questa pagina deve essere acceduta solo in GET */
    if($_SERVER["REQUEST_METHOD"] !== "GET") {  
      print_bad_request();
      return;
   }

   if(!$active_session) {
      print_sessions_required();
      return;
   }

   // unset useless SESSION variables
   unset($_SESSION["sign-up-info"]);
   unset($_SESSION["updated-pizza"]);

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

   /* in caso di LOGOUT da questa pagina, effettuo il logout reindirizzando l'utente alla pagina LOGIN */
   if(isset($_GET["logout"]) && $_GET["logout"] === "") {
      header("Location: ../login/login.php?logout");
      return;
   }

   require "../info/retrieve_available_pizzas.php";
   
   if($error_occurred && !$no_available_pizzas) 
      http_response_code(500);  //error retrieving data from db -> internal server error (500 http status code)
?> 

<!DOCTYPE html>
<html lang="it">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="author" content="Mario Mastrandrea - matricola 256226">
   <meta name="description" content="Sito esame PWR - Pagina ORDINA - Pagina utile ai soli utenti correttamente
      autenticati per effettuare nuovi ordini. Contiene un elenco di tutte le pizze ordinabili, fornendo per ciascuna
      di esse il nome, il prezzo unitario e un men&ugrave; a tendina per selezionare la quantit&agrave; desiderata. 
      Tramite il bottone &apos;Procedi&apos; &egrave; possibile andare alla pagina di riepilogo dell&apos;ordine">
   <meta name="keywords" content="ordina, ordine, pizze, ordinabili, men&ugrave;, 
      quantit&agrave;, tendina, form, html, conferma">
   <title>Pagina ORDINA - effettuazione ordine - Riservata agli utenti autenticati</title>
   <link rel="icon" href="../images/icons/<?= ($error_occurred && !$no_available_pizzas) ? 
      "red_error" : "shopping_cart" ?>_icon.png">
   <link rel="stylesheet" href="../styles.css">
   <link rel="stylesheet" href="ordina.css">
   <script src="../cambia/cambia.js"></script> <!-- useful for checkPizzaQty() function -->
   <script src="../form_checks.js"></script> <!-- useful for checkNotAllowedChars() function -->
   <script src="ordina.js"></script>
</head>

<body>
   <?= print_header_and_select("ordina", $is_user_authenticated, $is_user_manager) ?>

   <div class="main-container">
    
     <?php 
         if($error_occurred && !$no_available_pizzas) { //only when a db error occurs ?> 
            <h1>Si &egrave; verificato un errore <img src="../images/red_error.png" alt="red error icon" class="inline"></h1> <?php
         }
         else { ?>
            <h1>Effettua un nuovo ordine
               <img src="../images/shopping_cart.png" alt="shopping cart icon" class="inline">
            </h1> 
            <h2>Utente - <span class="user-name">
                  <?= "{$_SESSION["logged-in-user"]["name"]} {$_SESSION["logged-in-user"]["surname"]}" ?></span>
            </h2>  <?php 
         } 
      ?>

      <main id="order-menu-box" class="main-box">
         <?php 
            require "ordina_content.php";
         ?>
      </main>
      
   </div> 
   
   <?= print_footer_of_page("ordina") ?>
</body>

</html>