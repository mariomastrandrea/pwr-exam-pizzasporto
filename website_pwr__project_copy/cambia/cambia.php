<?php 
   require "../pages_utilities.php";
   require "../session_code.php";

   /* questa pagina deve essere acceduta solo in GET, o in POST tramite il form di questa stessa pagina */
   if($_SERVER["REQUEST_METHOD"] !== "GET" && $_SERVER["REQUEST_METHOD"] !== "POST") {  
      print_bad_request();
      return;
   }

   //sessions are required in order to access this page (user must be authenticated)
   if(!$active_session) {
      print_sessions_required();
      return;
   }
   
   unset($_SESSION["sign-up-info"]);

   // unset shopping_cart SESSION variables
   if(isset($_SESSION["pizzas-shopping-cart"]))
      foreach(array_keys($_SESSION["pizzas-shopping-cart"]) as $key)
         unset($_SESSION["pizzas-shopping-cart"][$key]);
      
   unset($_SESSION["pizzas-shopping-cart"]);
   unset($_SESSION["total-price"]); 

   if(!$is_user_authenticated || !$is_user_manager) { 
      //user is not authenticated as manager user -> forbidden access
      print_forbidden("Pagina accessibile solo ai gestori della pizzeria");
      return;
   }

   require "retrieve_menu.php";

   /* in caso di LOGOUT da questa pagina, effettuo il logout reindirizzando l'utente alla pagina LOGIN */
   if(isset($_GET["logout"]) && $_GET["logout"] === "") {
      header("Location: ../login/login.php?logout");
      return;
   }
   
   $input_error_occurred = false;

   if($_SERVER["REQUEST_METHOD"] === "POST" && !$error_occurred) { 
      //input checks and db updates
      require "input_checks_and_update_qty.php";
      require "input_checks_and_add_new_pizza.php";

      $input_error_occurred = count($error_messages) > 0;   
   }

   if($error_occurred && !$no_pizzas) http_response_code(500);  //error retrieving data from db -> internal server error
   elseif($input_error_occurred) http_response_code(400);    //client error
?>

<!DOCTYPE html>
<html lang="it">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="author" content="Mario Mastrandrea - matricola 256226">
   <meta name="description" content="Sito esame PWR - Pagina CAMBIA - Pagina accessibile solo ai gestori della
      pizzeria. Utile sia ad aggiungere una nuova pizza al men&ugrave;, specificandone nome, ingredienti, prezzo, 
      tipo e quantit&agrave;, e sia ad aggiornare la quantit&agrave; di una pizza gi&agrave; esistente">
   <meta name="keywords" content="gestori, gestore, men&ugrave;, quantit&agrave;, modifica, aggiunta, nuova, pizza,
      ingredienti, nome, tipo, vegan, veggy, form">
   <title>Pagina CAMBIA - <?= ($_SERVER["REQUEST_METHOD"] === "POST" && $input_error_occurred) ? "Errore" : 
            "aggiunta e aggiornamento pizze - Riservata ai gestori" ?></title>
   <link rel="icon" href="../images/icons/admin_icon.png">
   <link rel="stylesheet" href="../styles.css">
   <link rel="stylesheet" href="cambia.css">
   <script src="cambia.js"></script>
   <script src="../form_checks.js"></script>
</head>

<body>
   <?= print_header_and_select("cambia", $is_user_authenticated, $is_user_manager) ?>

   <div class="main-container">
      <?php 
         if($error_occurred && !$no_pizzas) { //only when a db error occurs ?> 
            <h1>Si &egrave; verificato un errore <img src="../images/red_error.png" alt="red error icon" class="inline"></h1> <?php
         }
         else { ?>
            <h1>Pagina di gestione del men&ugrave; 
               <img src="../images/admin_black.png" alt="admin black icon" class="inline"></h1>
            <h2>
               Gestore - <span class="manager-name">
                  <?= "{$_SESSION["logged-in-user"]["name"]} {$_SESSION["logged-in-user"]["surname"]}" ?></span>
            </h2> <?php 
         } 
      ?>

      <?php 
         /* se una delle 2 operazioni di aggiornamento del db è andata a buon fine, stampo solo il messaggio di successo dell'operazione; altrimenti, stampo il contenuto della pagina originale */
         if($_SERVER["REQUEST_METHOD"] === "POST" && !$error_occurred && !$input_error_occurred) {
            // DB successfully updated ?>

            <main class="main-box" id="operation-success-box">
               <?= $success_message ?>

               <div class="buttons-row">
                  <a href="<?= $_SERVER["PHP_SELF"] ?>" class="button-style">Torna alla gestione del men&ugrave;</a>
               </div>
            </main> <?php
         }
         else {  ?>

            <main>
               <h3>Modifica le quantit&agrave; delle pizze</h3>
               <div class="main-box" id="update-pizza-qty-box">
                  <?php 
                     require "cambia_update_qty_content.php";
                  ?>
               </div>

               <?php 
                  if(!$error_occurred) { ?>

                  <h3>Aggiungi una nuova pizza</h3>
                  <div class="main-box" id="add-new-pizza-box">
                     <?php 
                        require "cambia_add_pizza_content.php";
                     ?>
                  </div> <?php
               } ?>
            </main> <?php
         }
      ?> 
   </div> 
   
   <?= print_footer_of_page("cambia") ?>
</body>

</html>