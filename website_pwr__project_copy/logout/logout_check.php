<?php 
   // * to be required AFTER 'session_code.php' *

   if(isset($_GET["logout"]) && $_GET["logout"] === "" &&
         $active_session && $is_user_authenticated) {    /* eseguo il logout solo se ricevo un parametro in GET vuoto con chiave 'logout' e se l'utente risulta autenticato */
      //logout:

      // (1) unset login SESSION variables
      unset($_SESSION["is-user-authenticated"]);

      unset($_SESSION["logged-in-user"]["name"]);
      unset($_SESSION["logged-in-user"]["surname"]);
      unset($_SESSION["logged-in-user"]["birthdate"]);
      unset($_SESSION["logged-in-user"]["address"]);
      unset($_SESSION["logged-in-user"]["money"]);
      unset($_SESSION["logged-in-user"]["username"]);
      unset($_SESSION["logged-in-user"]["is-user-manager"]);
      unset($_SESSION["logged-in-user"]);

      // (2) unset also shopping_cart SESSION variables
      if(isset($_SESSION["pizzas-shopping-cart"]))
         foreach(array_keys($_SESSION["pizzas-shopping-cart"]) as $key)
            unset($_SESSION["pizzas-shopping-cart"][$key]);
      
      unset($_SESSION["pizzas-shopping-cart"]);
      unset($_SESSION["total-price"]);      

      // (3) store temporarily other session data (e.g. sign-up-info data)
      $other_session_data = array();

      foreach($_SESSION as $key => $value)  
         $other_session_data[$key] = $value;

      // (4) delete current SESSION

      $_SESSION = array();

      /* cancello sul client il cookie associato alla sessione, impostandogli una scadenza arbitraria nel passato */
      $session_parameters = session_get_cookie_params(); 
      setcookie(session_name(), "", time() - 1000000, //arbitrary high value
                  $session_parameters["path"], $session_parameters["domain"], 
                  $session_parameters["secure"], $session_parameters["httponly"]);
      
      session_destroy();   /* cancello i dati della sessione dal filesystem del server */
      /* questo è il momento in cui tutti i dati di sessione sono stati effettivamente eliminati nel server */

      // (5) update session local variables
      $is_user_authenticated = false;
      $is_user_manager = false;

      // (6) start new session & re-store other session data
      $active_session = session_start();

      if($active_session) {
         foreach($other_session_data as $key => $value)
            $_SESSION[$key] = $value;
      }

      /* se non avviassi una nuova sessione reimpostando i vecchi dati, perderei gli eventuali 
         dati di sessione sui tentativi di registrazione (vedi ../registra/input_checks.php riga 28) */
   }
?>