<?php 
   require "../pages_utilities.php";
   require "../session_code.php";

   if($active_session) {
      unset($_SESSION["updated-pizza"]);

      // unset shopping_cart SESSION variables
      if(isset($_SESSION["pizzas-shopping-cart"]))
         foreach(array_keys($_SESSION["pizzas-shopping-cart"]) as $key)
            unset($_SESSION["pizzas-shopping-cart"][$key]);
      
      unset($_SESSION["pizzas-shopping-cart"]);
      unset($_SESSION["total-price"]); 
   }

   /* questa pagina deve essere acceduta solo in GET */
   if($_SERVER["REQUEST_METHOD"] !== "GET") {  
      print_bad_request();
      return;
   }

   require "../logout/logout_check.php";
?>

<!DOCTYPE html>
<html lang="it">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="author" content="Mario Mastrandrea - matricola 256226">
   <meta name="description" content="Sito esame PWR - Pagina REGISTRA - Pagina di registrazione di un nuovo
      utente, inserendo le proprie informazioni personali e i dati relativi al proprio account">
   <meta name="keywords" content="registra, registrazione, nome, cognome, dataDiNascita, indirizzo, domicilio,
      credito, username, password, form, account">
   <title>Pagina REGISTRA - registrazione nuovo utente</title>
   <link rel="icon" href="../images/icons/sign_up_icon.png">
   <link rel="stylesheet" href="../styles.css">
   <link rel="stylesheet" href="registra.css">
   <script src="registra.js"></script>
   <script src="../form_checks.js"></script> 
</head>

<body>
   <?= print_header_and_select("registra", $is_user_authenticated, $is_user_manager) ?>

   <div class="main-container">
      <h1>Registrazione nuovo utente <img src="../images/sign_up_black.png" alt="sign up black icon" class="inline"></h1>

      <div class="main-box" id="sign-up-box">
         <?php 
            require "sign_up_content.php";
          ?>
      </div>
   </div> 
   
   <?= print_footer_of_page("registra") ?>
</body>

</html>