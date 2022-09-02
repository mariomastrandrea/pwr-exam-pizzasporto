<?php 
   require "../pages_utilities.php";
   require "../session_code.php";

   if($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["logout"]) && $_GET["logout"] === "" &&
      $active_session && $is_user_authenticated) {
      /* do all'utente la possibilità di fare il LOGOUT anche da questa pagina(con una GET request), 
         che solitamente è acceduta in POST, mediante redirect alla HOME page */
      header("Location: ../home/home.php?logout");
      return;
   }

   if($active_session) {
      unset($_SESSION["updated-pizza"]);

      // unset shopping_cart SESSION variables
      if(isset($_SESSION["pizzas-shopping-cart"]))
         foreach(array_keys($_SESSION["pizzas-shopping-cart"]) as $key)
            unset($_SESSION["pizzas-shopping-cart"][$key]);
      
      unset($_SESSION["pizzas-shopping-cart"]);
      unset($_SESSION["total-price"]); 
   }

   /* questa pagina deve essere acceduta solo in POST, tramite il form della pagina REGISTRA */
   if($_SERVER["REQUEST_METHOD"] !== "POST") {  
      print_bad_request();
      return;
   }

   require "input_checks.php";

   if($input_error_occurred) http_response_code(400);
?>

<!DOCTYPE html>
<html lang="it">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="author" content="Mario Mastrandrea - matricola 256226">
   <meta name="description" content="Sito esame PWR - Pagina REGISTRA (landing page) - Pagina per 
      il controllo dei dati e per l'effettuazione della registrazione">
   <meta name="keywords" content="registra, registrazione, nome, cognome, dataDiNascita, indirizzo, domicilio,
      credito, username, password, form, account">
   <title>Pagina REGISTRA - <?= $input_error_occurred ? "errore" : "controllo" ?> registrazione</title>
   <link rel="icon" href="../images/icons/<?= $input_error_occurred ? "red_error" : "sign_up" ?>_icon.png">
   <link rel="stylesheet" href="../styles.css">
   <link rel="stylesheet" href="registra.css">
</head>

<body>
   <?= print_header_and_select("registra", $is_user_authenticated, $is_user_manager) ?>

   <div class="main-container">
      <h1>  <?php 
         if($input_error_occurred) 
            echo "Si &egrave; verificato un errore <img src=\"../images/red_error.png\" alt=\"red error icon\" class=\"inline\">";
         else
            echo "Registrazione avvenuta con successo! <img src=\"../images/green_checkmark.png\" alt=\"green checkmark icon\" class=\"inline\">"; ?> 
      </h1>      

      <div class="main-box" id="landing-page-box">
         <?php 
            require "landing_page_content.php";
          ?>
      </div>
   </div> 
   
   <?= print_footer_of_page($input_error_occurred ? null : "registra") ?>
</body>

</html>