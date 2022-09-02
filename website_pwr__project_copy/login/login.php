<?php 
   require "../pages_utilities.php";
   require "../session_code.php";

   /* questa pagina deve essere acceduta solo in GET, oppure in POST (sottomenttendo il form) */
    if($_SERVER["REQUEST_METHOD"] !== "GET" && $_SERVER["REQUEST_METHOD"] !== "POST") {  
      print_bad_request();
      return;
   }

   // an active session is required to access this page
   if(!$active_session) {
      print_sessions_required();
      return;
   }
   
   //unset useless SESSION variables
   unset($_SESSION["sign-up-info"]);
   unset($_SESSION["updated-pizza"]);

   // unset shopping_cart SESSION variables
   if(isset($_SESSION["pizzas-shopping-cart"]))
      foreach(array_keys($_SESSION["pizzas-shopping-cart"]) as $key)
         unset($_SESSION["pizzas-shopping-cart"][$key]);
      
   unset($_SESSION["pizzas-shopping-cart"]);
   unset($_SESSION["total-price"]); 
   
   if($_SERVER["REQUEST_METHOD"] === "GET") {
      require "../logout/logout_check.php";

      /* L'utente non aveva accesso a questa pagina: lo reindirizzo alla pagina INFO */
      if($is_user_authenticated) {  
         header("Location: ../info/info.php");
         return;
      }
      /* riprendo l'ultimo username corretto (se esiste) */
      $last_correct_username = trim($_COOKIE["last-correct-username"] ?? ""); 
   }
   elseif($_SERVER["REQUEST_METHOD"] === "POST") {
      require "login_checks_and_save_data.php";

      if(!$input_error_occurred) {
         //user successfully logged in -> redirect to INFO page
         header("Location: ../info/info.php?logged"); /* passo un parametro in GET alla pagina INFO, così da riconoscere che prevengo da un login effettuato, e mostrare di conseguenza un messaggio di benvenuto all'utente */
         return;
      }

      $last_correct_username = $is_username_ok ? $username_input : "";  
   }

   $last_correct_password = !empty($is_password_ok) ? $password_input : "";   //it must exist also in GET request

   /* alcune variabili(ad es. '$last_correct_password', '$is_password_ok', ecc.) risultano superflue 
      se (come in questo caso) dopo il login si re-indirizza il client ad un'altra pagina. Ma qualora 
      così non fosse (ovvero, togliendo il redirect), il codice continuerebbe a funzionare ugualmente, 
      mostrando il successo dell'autenticazione su questa stessa pagina */
?>

<!DOCTYPE html>
<html lang="it">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="author" content="Mario Mastrandrea - matricola 256226">
   <meta name="description" content="Sito esame PWR - Pagina LOGIN - Pagina per l'effettuazione del login
      di un utente gi&agrave; registrato, con username e password. Il campo &apos;username&apos; deve
      essere precompilato con lo username dell'ultimo accesso andato a buon fine nelle ultime 72 ore">
   <meta name="keywords" content="login, accesso, pulisci, ok, username, password, form, account">
   <title> Pagina LOGIN - 
      <?= $_SERVER["REQUEST_METHOD"] === "GET" ? "Pagina di login al portale" : 
            ($input_error_occurred ? "Login ERRATO" : "Login effettuato correttamente") ?>
   </title>
   <link rel="icon" href="../images/icons/sign_in_icon.png">
   <link rel="stylesheet" href="../styles.css">
   <link rel="stylesheet" href="login.css">
   <!-- /* riutilizzo le stesse funzioni di validazione della pagina REGISTRA */ -->
   <script src="../registra/registra.js"></script>  
   <script src="../form_checks.js"></script>
</head>

<body>
   <?= print_header_and_select("login", $is_user_authenticated, $is_user_manager) ?>

   <div class="main-container">
      <h1>
         <?php 
            if($_SERVER["REQUEST_METHOD"] === "GET") { ?>
               Pagina di Login <img src="../images/yellow_key.png" alt="yellow key icon" class="inline"> <?php
            }
            elseif($_SERVER["REQUEST_METHOD"] === "POST") {
               if($input_error_occurred) { ?>
                  Errore di autenticazione <img src="../images/not_authenticated.png" alt="not authenticated icon" class="inline"> <?php
               }
               else { ?>
                  Login effettuato correttamente <img src="../images/authenticated.png" alt="authenticated icon" class="inline"> <?php
               }
            }
         ?>
      </h1>
      <h2><?= !empty($input_error_occurred) ? "Ritenta il login" : "" ?></h2> <!-- lascio questo <h2> anche se vuoto, per non generare allargamenti/restringimenti della pagina -->
 
      <?php 
         require "login_content.php";
      ?>
      
   </div> 
   
   <?= print_footer_of_page("login") ?>
</body>

</html>