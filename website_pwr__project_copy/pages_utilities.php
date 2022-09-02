<?php

   //menu pages info
   $menu_items = array(
      "home" => array(
         "href" => "../home/home.php",
         "name" => "HOME",
         "selected" => "",
         "disabled" => "",
         "description" => "Pagina di presentazione generale del sito",
         "title" => "Vai alla home page"
      ),
      "registra" => array(
         "href" => "../registra/registra.php",
         "name" => "REGISTRA",
         "selected" => "",
         "disabled" => "",
         "description" => "Pagina per la registrazione di un nuovo utente al portale, inserendo tutte le proprie informazioni",
         "title" => "Vai alla pagina di registrazione"
      ),
      "cambia" => array(
         "href" => "../cambia/cambia.php",
         "name" => "CAMBIA",
         "selected" => "",
         "disabled" => "",
         "description" => "Pagina riservata ai gestori per la modifica delle quantit&agrave; e l'aggiunta di nuove pizze",
         "title" => "Pagina creazione e aggiornamento pizze (manager-only)"
      ),
      "info" => array(
         "href" => "../info/info.php",
         "name" => "INFO",
         "selected" => "",
         "disabled" => "",
         "description" => "Pagina per la visualizzazione di tutte le pizze disponibili presenti nel menù",
         "title" => "Elenco pizze ordinabili"
      ),
      "login" => array(
         "href" => "../login/login.php",
         "name" => "LOGIN",
         "selected" => "",
         "disabled" => "",
         "description" => "Pagina per il login dell'utente con username e password",
         "title" => "Vai alla pagina di login"
      ),
      "ordina" => array(
         "href" => "../ordina/ordina.php",
         "name" => "ORDINA",
         "selected" => "",
         "disabled" => "",
         "description" => "Pagina per l'effettuazione di nuovi ordini, specificando tipo e quantit&agrave; delle pizze desiderate",
         "title" => "Vai alla pagina delle ordinazioni"
      ),
      "logout" => array(
         "href" => "{$_SERVER['PHP_SELF']}?logout",
         "name" => "LOGOUT",
         "selected" => "",
         "disabled" => "",
         "description" => "",
         "title" => "Effettua il logout"
      ),
      "conferma" => array(
         "href" => "../conferma/conferma.php",
         "name" => "CONFERMA",
         "selected" => "",
         "disabled" => "",
         "description" => "Pagina di riepilogo dell'ordine e indicazione dell'indirizzo e dell'orario di consegna",
         "title" => ""
      ),
      "finale" => array(
         "href" => "../finale/finale.php",
         "name" => "FINALE",
         "selected" => "",
         "disabled" => "",
         "description" => "Pagina di controllo e di esecuzione finale dell'ordine",
         "title" => ""
      )
      //add here new pages
   );
   
   function print_header_and_select($page_selected, $is_user_authenticated, $is_user_manager) {

      global $menu_items;

      /* normalizzo le 2 variabili di controllo a valori booleani */
      $is_user_manager = $is_user_manager === true ? true : false;
      $is_user_authenticated = $is_user_authenticated === true ? true : false;

      //manage user privileges

      if(isset($page_selected) && array_key_exists($page_selected, $menu_items) 
         && $page_selected !== "finale") {   /* all'interno della pagina FINALE, non visualizzo alcuna pagina come selezionata */

         /* all'interno della pagina CONFERMA, visualizzo come selezionata la pagina ORDINA */
         if($page_selected === "conferma")   
            $menu_items["ordina"]["selected"] = "selected";
         else
            $menu_items[$page_selected]["selected"] = "selected";
      }

      if(!$is_user_manager) 
         disable_page("cambia", $menu_items);

      if($is_user_authenticated) {
         disable_page("login", $menu_items);
      }
      else {
         disable_page("logout", $menu_items);
      }

      //print

      $menu_output = array();

      foreach($menu_items as $item) {
         if($item["name"] === "CONFERMA" || $item["name"] === "FINALE") continue;   /* non metto nel menù le pagine CONFERMA e FINALE */

         $menu_open_tag_element = $item["disabled"] === "disabled" ? "div" : "a href=\"{$item['href']}\"";
         $menu_end_tag_element = $item["disabled"] === "disabled" ? "div" : "a";

         array_push($menu_output, 
            "<$menu_open_tag_element class=\"menu-item {$item['selected']} {$item['disabled']}\" title=\"{$item['title']}\">
               {$item['name']}
            </$menu_end_tag_element>");
      }
      
      $menu_output = join("\n", $menu_output);

      /* prendo i dati dell'utente, se è loggato */
      $username = $_SESSION["logged-in-user"]["username"] ?? "Anonimo";
      $money = number_format($_SESSION["logged-in-user"]["money"] ?? 0.0, 2);

      /* presento l'header in funzione dei privilegi dell'utente */
      $username_title = $is_user_authenticated ? ($is_user_manager ? "Utente gestore &apos;$username&apos; autenticato" :
                         "Utente &apos;$username&apos; autenticato") : "Utente non autenticato";
      $user_image_name = $is_user_authenticated ? ($is_user_manager ? "manager_user" : "user") : "anonymous_user";
      $money_title = $money === "0.00" ? "Portafoglio vuoto" : "Il tuo portafoglio: &euro; $money";
      
      $user_info_output =  
         "<div class=\"username-box\" title=\"$username_title\">
            <span class=\"username\">$username</span>
            <img src=\"../images/$user_image_name.png\" alt=\"user icon\" class=\"inline\">
         </div>
         <div class=\"money-box\" title=\"$money_title\">
            <span class=\"money\" id=\"user-current-money\">&euro; $money</span>
            <img src=\"../images/money.png\" alt=\"money icon\" class=\"inline\">
         </div>";

      $website_title_box = 
         "<div class=\"website-title\" title=\"Nome del sito\">
            <a href=\"../home/home.php\">OrdinaPizze.si</a>
         </div>";
      
      $header_output = 
         "<header class=\"row-header\">
            <nav class=\"menu-flex-row\">
               $menu_output
            </nav>
            $website_title_box
            <div class=\"user-info-flex-row\">
               $user_info_output
            </div>
         </header>";

      return $header_output;
   }

   function disable_page($page_name, &$menu_items) {       /* l'array menu_items deve essere passato by reference */
      $menu_items[$page_name]["disabled"] = "disabled";
   }

   function select_page($page_name, &$menu_items) {       /* l'array menu_items deve essere passato by reference */
      $menu_items[$page_name]["selected"] = "selected";
   }

   function print_footer_of_page($page_name) { 
      global $menu_items;
      $page_description = isset($page_name) ? $menu_items[$page_name]["description"] : "Errore";
      $page_link = isset($page_name) ? $menu_items[$page_name]["href"] : "#"; ?>

      <div class="separator"></div> 
      <!-- to separate footer from page content -->

      <footer class="footer-flex-row">
         <div class="footer-content">

            <div class="page-info">
               <p>Esame Pwr - Pagina <a href="<?= $page_link ?>" class="<?= isset($page_name) ? "page-name" : "error-page" ?>">
                  <?= $page_name ?? "non disponibile" ?></a> - &copy; Mario Mastrandrea</p>
               <p class="page-description"><?= $page_description ?></p>
            </div>

            <div class="attributions">
               <div class="author-attribution">Icons made by <a href="https://www.freepik.com" title="Freepik">
                  Freepik</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a></div>

               <div class="author-attribution">Icons made by <a href="https://smashicons.com/" title="Smashicons">
                  Smashicons</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a></div>
            </div>

         </div>
      </footer> <?php 
   }

   function pizza_li_icon() {
      echo "<img class=\"inline li-icon\" src=\"../images/pizza.png\" alt=\"pizza icon\">";
   }

   function print_bad_request() { 
      http_response_code(400); /* imposto un generico HTTP status code di 400 (Bad Request) --> errore generato dal client */  
      $is_user_authenticated = $GLOBALS['is_user_authenticated'] ?? false;
      $is_user_manager = $GLOBALS["is_user_manager"] ?? false ?> 

      <!DOCTYPE html>
      <html lang="it">

      <head>
         <meta charset="utf-8">
         <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <meta name="author" content="Mario Mastrandrea - matricola 256226">
         <meta name="description" content="Bad Request - 400 - Errore: pagina non disponibile">
         <meta name="keywords" content="400, bad, request, errore">
         <title>400 - Bad Request</title>
         <link rel="icon" href="../images/icons/error_icon.png">
         <link rel="stylesheet" href="../styles.css">
      </head>

      <body>
         <?= print_header_and_select(null, $is_user_authenticated, $is_user_manager) ?>

         <div class="main-container">
            <h1>Pagina non disponibile</h1>
            <h2>Error 400</h2>

            <main class="error-box">
               <p>Errore: la pagina richiesta non è disponibile</p>
            </main>
            
         </div> 
         
         <?= print_footer_of_page(null) ?>
      </body>

      </html> <?php
   }

   function print_sessions_required() { 
      http_response_code(400); ?>
      
      <!DOCTYPE html>
      <html lang="it">

      <head>
         <meta charset="utf-8">
         <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <meta name="author" content="Mario Mastrandrea - matricola 256226">
         <meta name="description" content="400 - Errore: sessione non avviata. Sessioni necessarie per
            accedere a questa pagina">
         <meta name="keywords" content="400, errore, sessione, necessaria">
         <title>400 - Sessione non attiva</title>
         <link rel="icon" href="../images/icons/error_icon.png">
         <link rel="stylesheet" href="../styles.css">
      </head>

      <body>
         <?= print_header_and_select(null, false, false) ?>

         <div class="main-container">
            <h1>Sessione non attiva</h1>
            <h2>Pagina non disponibile - Bad Request</h2>

            <main class="error-box">
               <p>Errore: la pagina richiesta richiede una sessione attiva</p>
            </main>
            
         </div> 
         
         <?= print_footer_of_page(null) ?>
      </body>

      </html> <?php
   }

   function print_forbidden($message) { 
      http_response_code(403); // FORBIDDEN http status code 
      $is_user_authenticated = $GLOBALS['is_user_authenticated'] ?? false;
      $is_user_manager = $GLOBALS["is_user_manager"] ?? false ?>   
      
      <!DOCTYPE html>
      <html lang="it">

      <head>
         <meta charset="utf-8">
         <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <meta name="author" content="Mario Mastrandrea - matricola 256226">
         <meta name="description" content="Forbidden - 403 - Errore: utente non autorizzato. L'utente
            non dispone dei privilegi necessari per accedere a questa pagina">
         <meta name="keywords" content="403, forbidden, user, unauthorized, errore">
         <title>403 - Forbidden</title>
         <link rel="icon" href="../images/icons/error_icon.png">
         <link rel="stylesheet" href="../styles.css">
      </head>

      <body>
         <?= print_header_and_select(null, $is_user_authenticated, $is_user_manager) ?>

         <div class="main-container">
            <h1>Pagina non disponibile</h1>
            <h2>Error 403 - Forbidden</h2>

            <main class="error-box">
               <p>Errore: utente non autorizzato ad accedere alla pagina</p>
               <?= $message ? "<p>$message</p>" : "" ?>
            </main>
            
         </div> 
         
         <?= print_footer_of_page(null) ?>
      </body>

      </html> <?php
   }

   function print_unauthorized() { 
      http_response_code(401); // UNAUTHORIZED http status code ?>   
      
      <!DOCTYPE html>
      <html lang="it">

      <head>
         <meta charset="utf-8">
         <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <meta name="author" content="Mario Mastrandrea - matricola 256226">
         <meta name="description" content="Unauthorized - 401 - Errore: utente non autorizzato. 
            L'utente deve essere autenticato per poter accedere a questa pagina">
         <meta name="keywords" content="401, unauthorized, user, errore, autenticazione">
         <title>401 - Unauthorized</title>
         <link rel="icon" href="../images/icons/error_icon.png">
         <link rel="stylesheet" href="../styles.css">
      </head>

      <body>
         <?= print_header_and_select(null, false, false) ?>

         <div class="main-container">
            <h1>Pagina non disponibile</h1>
            <h2>Error 401 - Unauthorized</h2>

            <div class="main-box error-wrapper"> 
               <main class="error-box">
                  <p><span class="error">Attenzione</span>! questa pagina è accessibile solo previa autenticazione.</p>
               </main>

               <div class="buttons-row">
                  <a href="../login/login.php" class="button-style">Vai al login</a>
               </div>
            </div>

         </div> 
         
         <?= print_footer_of_page(null) ?>
      </body>

      </html> <?php
   }
   
   function char_quote_wrap($char) {

      if(preg_match("/^[\s]$/", $char))
         return "(spazio)";

      return "'$char'";
   }

   function error_p_wrap($error_message) {
      return "<p> - $error_message</p>";
   }

   function error_li_wrap($error_message) {
      return "<li>$error_message</li>";
   }

   function store_in_session($is_name_ok, $is_surname_ok, $is_birthdate_ok, $is_address_ok, 
      $is_money_ok, $is_nick_ok, $is_password_ok, $new_user_info) {

         if(!isset($_SESSION)) return;

         $_SESSION["sign-up-info"] = array();

         if($is_name_ok)      $_SESSION["sign-up-info"]["name"]      = $new_user_info["name"];
         if($is_surname_ok)   $_SESSION["sign-up-info"]["surname"]   = $new_user_info["surname"];
         if($is_birthdate_ok) $_SESSION["sign-up-info"]["birthdate"] = $new_user_info["birthdate"];
         if($is_address_ok)   $_SESSION["sign-up-info"]["address"]   = $new_user_info["address"];
         if($is_money_ok)     $_SESSION["sign-up-info"]["money"]     = $new_user_info["money"];
         if($is_nick_ok)      $_SESSION["sign-up-info"]["nick"]      = $new_user_info["nick"];
         if($is_password_ok)  $_SESSION["sign-up-info"]["password"]  = $new_user_info["password"];
   }
?>