<?php 
   require "../pages_utilities.php";
   require "../session_code.php";

    /* questa pagina deve essere acceduta solo in GET */
    if($_SERVER["REQUEST_METHOD"] !== "GET") {  
      print_bad_request();
      return;
   }

   if($active_session) {
      //unset useless SESSION variables
      unset($_SESSION["sign-up-info"]);
      unset($_SESSION["updated-pizza"]);

      // unset shopping_cart SESSION variables
      if(isset($_SESSION["pizzas-shopping-cart"]))
         foreach(array_keys($_SESSION["pizzas-shopping-cart"]) as $key)
            unset($_SESSION["pizzas-shopping-cart"][$key]);
      
      unset($_SESSION["pizzas-shopping-cart"]); 
      unset($_SESSION["total-price"]); 
   }

   require "../logout/logout_check.php";
   require "retrieve_available_pizzas.php";
?>

<!DOCTYPE html>
<html lang="it">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="author" content="Mario Mastrandrea - matricola 256226">
   <meta name="description" content="Sito esame PWR - Pagina INFO - Pagina contenente il men&ugrave;
      con l'elenco di tutte le pizze disponibili (con quantit&grave; non nulla). Per ciascuna pizza
      sono specificati: nome, ingredienti, prezzo, se si tratta di cibo vegano o vegetariano, e, qualora
      l'utente sia autenticato, anche la quantit&agrave; disponibile">
   <meta name="keywords" content="info, men&ugrave;, pizze, nome, ingredienti, prezzo, unitario, 
      vegano, vegetariano, quantit&agrave;, benvenuto">
   <title>Pagina INFO - men&ugrave; pizze ordinabili</title>
   <link rel="icon" href="../images/icons/<?= $error_occurred ? "red_error" : "pizza_box" ?>_icon.png">
   <link rel="stylesheet" href="../styles.css">
   <link rel="stylesheet" href="info.css">
</head>

<body>
   <?= print_header_and_select("info", $is_user_authenticated, $is_user_manager) ?>

   <div class="main-container">
    
      <h1>  <?php 
         if($error_occurred && !$no_available_pizzas) { //only when a db error occurs ?> 
            Si &egrave; verificato un errore <img src="../images/red_error.png" alt="red error icon" class="inline"> <?php
         }
         else { ?>
            Men&ugrave; pizze ordinabili 
               <img src="../images/icons/pizza_box_icon.png" alt="pizza box icon" class="inline"> <?php 
         } ?>
      </h1>  

      <?php 
         if(isset($_GET["logged"]) && $_GET["logged"] === "" && $active_session && $is_user_authenticated) { 
            /* l'utente si è appena loggato con successo dalla pagina LOGIN */   ?>
            <h2>Benvenuto <em class="oblique"><?= $_SESSION["logged-in-user"]["username"] ?></em>!</h2>

            <div id="welcome-message-box" class="main-box">
               <p>Login effettuato con successo da <span class="field-name">
                  <?= "{$_SESSION["logged-in-user"]["name"]} {$_SESSION["logged-in-user"]["surname"]}" ?></span> 
                  <img class="inline" src="../images/tongue_smile.png" alt="pizza icon">
               </p>
            </div>   <?php
         }
      ?>

      <main id="menu-info-box" class="main-box">
         <?php 
            require "info_content.php";
         ?>
      </main>
      
      <?php 
         if(!$error_occurred) { ?>
            <div class="main-box" id="suggestion-box">
               <p>Per effettuare un nuovo ordine <?= $is_user_authenticated ? 
                  " vai alla pagina <a href=\"../ordina/ordina.php\"><span class=\"page-name\">ORDINA</span></a>" : 
                  " effettua prima il <a href=\"../login/login.php\"><span class=\"page-name\">LOGIN</span></a> 
                  e poi vai alla pagina <span class=\"page-name disabled\">ORDINA</span>" ?> 
               </p>
            </div> <?php
         } ?>
   </div> 
   
   <?= print_footer_of_page("info") ?>
</body>

</html>