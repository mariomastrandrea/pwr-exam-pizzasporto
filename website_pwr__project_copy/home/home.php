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
?>

<!DOCTYPE html>
<html lang="it">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="author" content="Mario Mastrandrea - matricola 256226">
   <meta name="description" content="Sito esame PWR - Pagina HOME - Pagina di presentazione del sito: 
      contiene una descrizione generale sulle pagine e sulle funzionalit&agrave; dell'intero sito web">
   <meta name="keywords" content="home, descrizione, generale, sito, funzionalit&agrave;">
   <title>Pagina HOME - presentazione sito</title>
   <link rel="icon" href="../images/icons/home_icon.png">
   <link rel="stylesheet" href="../styles.css">
   <link rel="stylesheet" href="home.css">
</head>

<body>
   <?= print_header_and_select("home", $is_user_authenticated, $is_user_manager) ?>

   <div class="main-container">
      <h1>Pagina di presentazione <img src="../images/home_black.png" alt="home black icon" class="inline"></h1>

      <main id="website-info-container" class="main-box">
         <?php 
            require "welcome_content.php";
         ?>
      </main>

   </div> 
   
   <?= print_footer_of_page("home") ?>
</body>

</html>