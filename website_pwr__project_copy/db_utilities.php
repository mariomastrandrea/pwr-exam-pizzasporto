<?php 
   /* in tutte le funzioni di accesso al db, viene passato il vettore degli errori BY REFERENCE 
      (con '&' davanti al parametro, nella firma della funzione), così da modificarne il contenuto 
      e usufruirne anche all'esterno della funzione stessa */

   // * users table functions *
   
   function check_username_in_db(&$connection, &$statement, $username, &$error_messages) {

      if(!$connection || mysqli_connect_errno()) {
         $error_message = "";
   
         if($connection)
            $error_message = "<span class=\"error\">Errore</span>: " . mysqli_error($connection);
   
         array_push($error_messages, "Si &egrave; verificato un errore nella connessione al DB. $error_message");
         return null;
      }

      $query = "SELECT * 
                 FROM `pizzasporto`.`utenti` 
                 WHERE username = ?";
   
      $statement = mysqli_prepare($connection, $query);

      if(!$statement || mysqli_stmt_errno($statement)) {
         $error_message = "";
   
         if($statement)
            $error_message = "<span class=\"error\">Errore</span>: " . mysqli_stmt_error($statement);
   
         array_push($error_messages, "Si &egrave; verificato un errore nella costruzione della 
            query al DB. $error_message");
         return null;
      }

      mysqli_stmt_bind_param($statement, "s", $username);

      if(mysqli_stmt_errno($statement)) {
         $error_message = "<span class=\"error\">Errore</span>: " . mysqli_stmt_error($statement);
         array_push($error_messages, "Si &egrave; verificato un errore nel processamento dei parametri. $error_message");
         return null;
      }

      mysqli_stmt_execute($statement);

      if(mysqli_stmt_errno($statement)) {
         $error_message = "<span class=\"error\">Errore</span>: " . mysqli_stmt_error($statement);
         array_push($error_messages, "Si &egrave; verificato un errore nell'esecuzione della query. $error_message");
         return null;
      }

      $stored = mysqli_stmt_store_result($statement);
      if(!$stored) {
         array_push($error_messages, "Errore nel salvataggio dei risultati");
         return null; //error occurred
      }

      $num_rows = mysqli_stmt_num_rows($statement);

      if($num_rows === 0) {
         //this $username is not found in db -> no errors
         return false;
      }

      //username already present
      array_push($error_messages, "<span class=\"error\">Errore</span>: lo <span class=\"field-name\">username</span> scelto 
         &quot;$username&quot; non &egrave; pi&ugrave; disponibile. Scegli un altro username");
      return true;
   }

   function add_new_user_to_db(&$connection, &$statement, $new_user_info, &$error_messages) {

      if(!$connection || mysqli_connect_errno()) {
         $error_message = "";
   
         if($connection)
            $error_message = "<span class=\"error\">Errore</span>: " . mysqli_error($connection);
   
         array_push($error_messages, "Si &egrave; verificato un errore nella connessione al DB. $error_message");
         return false;
      }

      $query = "INSERT INTO `pizzasporto`.`utenti`
                        (nome, cognome, `data`, indirizzo, username, pwd, credito, gestore)  
                VALUES  (?, ?, ?, ?, ?, ?, ?, 0)";
   
      $statement = mysqli_prepare($connection, $query);

      if(!$statement || mysqli_stmt_errno($statement)) {
         $error_message = "";
   
         if($statement)
            $error_message = "<span class=\"error\">Errore</span>: " . mysqli_stmt_error($statement);
   
         array_push($error_messages, "Si &egrave; verificato un errore nella costruzione della 
            query al DB. $error_message");
         return false;
      }

      $name = $new_user_info["name"];
      $surname = $new_user_info["surname"];
      $birthdate = "{$new_user_info['birthdate-year']}-{$new_user_info['birthdate-month']}-{$new_user_info['birthdate-day-of-month']}";
      $address = $new_user_info["address"];
      $username = $new_user_info["nick"];
      $password = $new_user_info["password"];
      $float_money = floatval($new_user_info["money"]) * 100.0;
      $money_cents = intval($float_money);

      mysqli_stmt_bind_param($statement, "ssssssi", 
         $name, $surname, $birthdate, $address, $username, $password, $money_cents);
      
      if(mysqli_stmt_errno($statement)) {
         $error_message = "<span class=\"error\">Errore</span>: " . mysqli_stmt_error($statement);
         array_push($error_messages, "Si &egrave; verificato un errore nel processamento dei parametri. $error_message");
         return false;
      }

      mysqli_stmt_execute($statement);

      if(mysqli_stmt_errno($statement)) {
         $error_message = "<span class=\"error\">Errore</span>: " . mysqli_stmt_error($statement);
         array_push($error_messages, "Si &egrave; verificato un errore nell'esecuzione della query. $error_message");
         return false;
      }

      // new user successfully added here
      return true;
   }

   function retrieve_user_from_db(&$connection, &$statement, $username, &$error_messages) {

      if(!$connection || mysqli_connect_errno()) {
         $error_message = "";
   
         if($connection)
            $error_message = "<span class=\"error\">Errore</span>: " . mysqli_error($connection);
   
         array_push($error_messages, "Si &egrave; verificato un errore nella connessione al DB. $error_message");
         return null;
      }

      $query = "SELECT nome, cognome, `data`, indirizzo, username, pwd, credito, gestore 
                 FROM `pizzasporto`.`utenti` 
                 WHERE username = ?";
   
      $statement = mysqli_prepare($connection, $query);

      if(!$statement || mysqli_stmt_errno($statement)) {
         $error_message = "";
   
         if($statement)
            $error_message = "<span class=\"error\">Errore</span>: " . mysqli_stmt_error($statement);
   
         array_push($error_messages, "Si &egrave; verificato un errore nella costruzione della 
            query al DB. $error_message");
         return null;
      }

      mysqli_stmt_bind_param($statement, "s", $username);

      if(mysqli_stmt_errno($statement)) {
         $error_message = "<span class=\"error\">Errore</span>: " . mysqli_stmt_error($statement);
         array_push($error_messages, "Si &egrave; verificato un errore nel processamento dei parametri. $error_message");
         return null;
      }

      mysqli_stmt_execute($statement);

      if(mysqli_stmt_errno($statement)) {
         $error_message = "<span class=\"error\">Errore</span>: " . mysqli_stmt_error($statement);
         array_push($error_messages, "Si &egrave; verificato un errore nell'esecuzione della query. $error_message");
         return null;
      }

      mysqli_stmt_bind_result($statement, $db_name, $db_surname, $db_birthdate, $db_address, 
         $db_username, $db_password, $db_money, $db_is_user_manager);

      $there_are_data = mysqli_stmt_fetch($statement);

      if(mysqli_stmt_errno($statement)) { 
         $error_message = "<span class=\"error\">Errore</span>: " . mysqli_stmt_error($statement);
         array_push($error_messages, "Si &egrave; verificato un errore nel prelievo dei risultati. $error_message");
         return null;
      }

      if(!$there_are_data) {  // $username does not exist
         array_push($error_messages, "<span class=\"error\">Errore</span>: non esiste alcun utente con <span class=\"field-name\">username</span> 
            <em class=\"oblique\">&quot;$username&quot;</em>. <br> Ritenta il login");
         return null;
      }

      //username is correct here -> return user info
      return array(
         "name" => $db_name,
         "surname" => $db_surname,
         "birthdate" => $db_birthdate,
         "address" => $db_address,
         "money" => floatval($db_money) / 100.0,   //from cents to euros
         "username" => $db_username,
         "password" => $db_password,
         "is-user-manager" => $db_is_user_manager === 0 ? false : true
      );
   }

   // * pizzas table functions *

   function retrieve_all_pizzas_from_db(&$connection, &$statement, &$error_messages) {
      return retrieve_pizzas_with_min_qty(0, $connection, $statement, $error_messages);
   }

   function retrieve_available_pizzas_from_db(&$connection, &$statement, &$error_messages) {
      return retrieve_pizzas_with_min_qty(1, $connection, $statement, $error_messages);
   }

   function retrieve_pizzas_with_min_qty($min_qty, &$connection, &$statement, &$error_messages) {

      if(!$connection || mysqli_connect_errno()) {
         $error_message = "";
   
         if($connection)
            $error_message = "<span class=\"error\">Errore</span>: " . mysqli_error($connection);
   
         array_push($error_messages, "Si &egrave; verificato un errore nella connessione al DB. $error_message");
         return null;
      }

      if(!is_int($min_qty) || $min_qty < 0) {
         if($min_qty == null) 
            array_push($error_messages, "<span class=\"error\">Errore</span>: la quantit&agrave; 
               minima non pu&ograve; essere &apos;null&apos;.");
         else
            array_push($error_messages, "<span class=\"error\">Errore</span>: &quot;$min_qty&quot; 
               non &egrave; una quantit&agrave; minima valida.");
         return null;
      } 

      $query = "SELECT  id, ingredienti, nome, tipo, prezzo, qty 
                  FROM `pizzasporto`.`pizze`
                 WHERE  qty >= ?";     

      $statement = mysqli_prepare($connection, $query);

      if(!$statement || mysqli_stmt_errno($statement)) {
         $error_message = "";
   
         if($statement)
            $error_message = "<span class=\"error\">Errore</span>: " . mysqli_stmt_error($statement);
   
         array_push($error_messages, "Si &egrave; verificato un errore nella costruzione della 
            query al DB. $error_message");
         return null;
      }

      mysqli_stmt_bind_param($statement, "i", $min_qty);

      if(mysqli_stmt_errno($statement)) {
         $error_message = "<span class=\"error\">Errore</span>: " . mysqli_stmt_error($statement);
         array_push($error_messages, "Si &egrave; verificato un errore nel processamento dei parametri. $error_message");
         return null;
      }

      mysqli_stmt_execute($statement);

      if(mysqli_stmt_errno($statement)) {
         $error_message = "<span class=\"error\">Errore</span>: " . mysqli_stmt_error($statement);
         array_push($error_messages, "Si &egrave; verificato un errore nell'esecuzione della query. $error_message");
         return null;
      }

      $menu_pizzas = array();

      mysqli_stmt_bind_result($statement, $pizza_id, $pizza_ingredients, $pizza_name, 
                                 $pizza_type, $pizza_price, $pizza_qty);

      while(mysqli_stmt_fetch($statement)) {

         if(mysqli_stmt_errno($statement)) { 
            $error_message = "<span class=\"error\">Errore</span>: " . mysqli_stmt_error($statement);
            array_push($error_messages, "Si &egrave; verificato un errore nel prelievo dei risultati. $error_message");
            return null;
         }

         //retrieve new pizza
         $menu_pizzas[$pizza_id] = array(
            "id" => $pizza_id,
            "ingredients" => $pizza_ingredients,
            "name" => $pizza_name,
            "type" => $pizza_type ?? "-", /* lascio il campo 'tipo' così com'è se è diverso da 'null', altrimenti lo imposto a "-" */
            "price" => floatval($pizza_price) / 100.0,
            "qty" => $pizza_qty  // > 0 sure
         );
      }

      if(empty($menu_pizzas)) {  // there is not any available pizza  
         $error_text = "nel men&ugrave;";

         if($min_qty === 1) $error_text = "ordinabile $error_text";
         elseif($min_qty > 1) $error_text = "$error_text con quantit&agrave; maggiore o uguale a $min_qty";

         array_push($error_messages, "<span class=\"error\">Attenzione</span>: non esiste alcuna pizza $error_text!");
         return null;
      }

      return $menu_pizzas;
   }

   function update_pizza_qty_in_db(&$connection, &$statement, $pizza_id, $new_qty, &$error_messages) {

      if(!$connection || mysqli_connect_errno()) {
         $error_message = "";
   
         if($connection)
            $error_message = "<span class=\"error\">Errore</span>: " . mysqli_error($connection);
   
         array_push($error_messages, "Si &egrave; verificato un errore nella connessione al DB. $error_message");
         return false;
      }

      $query = "UPDATE `pizzasporto`.`pizze`
                   SET `qty` = ?          
                 WHERE `pizze`.`id` = ? ";
   
      $statement = mysqli_prepare($connection, $query);

      if(!$statement || mysqli_stmt_errno($statement)) {
         $error_message = "";
   
         if($statement)
            $error_message = "<span class=\"error\">Errore</span>: " . mysqli_stmt_error($statement);
   
         array_push($error_messages, "Si &egrave; verificato un errore nella costruzione della 
            query al DB. $error_message");
         return false;
      }

      mysqli_stmt_bind_param($statement, "ii", $new_qty, $pizza_id);
      
      if(mysqli_stmt_errno($statement)) {
         $error_message = "<span class=\"error\">Errore</span>: " . mysqli_stmt_error($statement);
         array_push($error_messages, "Si &egrave; verificato un errore nel processamento dei parametri. $error_message");
         return false;
      }

      mysqli_stmt_execute($statement);

      if(mysqli_stmt_errno($statement)) {
         $error_message = "<span class=\"error\">Errore</span>: " . mysqli_stmt_error($statement);
         array_push($error_messages, "Si &egrave; verificato un errore nell'esecuzione della query. $error_message");
         return false;
      }

      // new pizza qty successfully updated here
      return true;
   }

   function check_pizza_name_existence_in_db(&$connection, &$statement, $pizza_name, &$error_messages) {

      if(!$connection || mysqli_connect_errno()) {
         $error_message = "";
   
         if($connection)
            $error_message = "<span class=\"error\">Errore</span>: " . mysqli_error($connection);
   
         array_push($error_messages, "Si &egrave; verificato un errore nella connessione al DB. $error_message");
         return null;
      }

      $query = "SELECT  * 
                  FROM `pizzasporto`.`pizze` 
                 WHERE  nome = ?";
   
      $statement = mysqli_prepare($connection, $query);

      if(!$statement || mysqli_stmt_errno($statement)) {
         $error_message = "";
   
         if($statement)
            $error_message = "<span class=\"error\">Errore</span>: " . mysqli_stmt_error($statement);
   
         array_push($error_messages, "Si &egrave; verificato un errore nella costruzione della 
            query al DB. $error_message");
         return null;
      }

      mysqli_stmt_bind_param($statement, "s", $pizza_name);

      if(mysqli_stmt_errno($statement)) {
         $error_message = "<span class=\"error\">Errore</span>: " . mysqli_stmt_error($statement);
         array_push($error_messages, "Si &egrave; verificato un errore nel processamento dei parametri. $error_message");
         return null;
      }

      mysqli_stmt_execute($statement);

      if(mysqli_stmt_errno($statement)) {
         $error_message = "<span class=\"error\">Errore</span>: " . mysqli_stmt_error($statement);
         array_push($error_messages, "Si &egrave; verificato un errore nell'esecuzione della query. $error_message");
         return null;
      }

      $stored = mysqli_stmt_store_result($statement);
      if(!$stored) {
         array_push($error_messages, "Errore nel salvataggio dei risultati");
         return null; //error occurred
      }

      $num_rows = mysqli_stmt_num_rows($statement);

      if($num_rows === 0) {
         //this $pizza_name is not found in db -> no errors -> ok
         return false;
      }

      //pizza name already present
      array_push($error_messages, "<span class=\"error\">Errore</span>: esiste gi&agrave;
         nel men&ugrave; una pizza chiamata &quot;$pizza_name&quot;. Scegli un altro 
         <span class=\"field-name\">nome</span> per la nuova pizza");
      return true;
   }

   function add_new_pizza_to_db(&$connection, &$statement, $new_pizza_info, &$error_messages) {

      if(!$connection || mysqli_connect_errno()) {
         $error_message = "";
   
         if($connection)
            $error_message = "<span class=\"error\">Errore</span>: " . mysqli_error($connection);
   
         array_push($error_messages, "Si &egrave; verificato un errore nella connessione al DB. $error_message");
         return false;
      }

      $query = "INSERT INTO `pizzasporto`.`pizze`
                        (ingredienti, nome, tipo, prezzo, qty)  
                VALUES  (?, ?, ?, ?, ?)";
   
      $statement = mysqli_prepare($connection, $query);

      if(!$statement || mysqli_stmt_errno($statement)) {
         $error_message = "";
   
         if($statement)
            $error_message = "<span class=\"error\">Errore</span>: " . mysqli_stmt_error($statement);
   
         array_push($error_messages, "Si &egrave; verificato un errore nella costruzione della 
            query al DB. $error_message");
         return false;
      }

      //change 'price' from X.XX(float) to XXX(int) - (from euros to cents)
      $float_price = floatval($new_pizza_info["price"]) * 100.0;
      $price_cents = intval($float_price);
      $new_pizza_info["price"] = $price_cents;

      //change pizza type
      if($new_pizza_info["type"] === "-")
         $new_pizza_info["type"] = null; 

      mysqli_stmt_bind_param($statement, "sssii", $new_pizza_info["ingredients"], 
         $new_pizza_info["name"], $new_pizza_info["type"], $new_pizza_info["price"], $new_pizza_info["qty"]);
      
      if(mysqli_stmt_errno($statement)) {
         $error_message = "<span class=\"error\">Errore</span>: " . mysqli_stmt_error($statement);
         array_push($error_messages, "Si &egrave; verificato un errore nel processamento dei parametri. $error_message");
         return false;
      }

      mysqli_stmt_execute($statement);

      if(mysqli_stmt_errno($statement)) {
         $error_message = "<span class=\"error\">Errore</span>: " . mysqli_stmt_error($statement);
         array_push($error_messages, "Si &egrave; verificato un errore nell'esecuzione della query. $error_message");
         return false;
      }

      // new pizza successfully added here
      return true;
   }

   function decrease_pizzas_quantities_in_db(&$connection, &$statement, $pizzas_quantities, &$error_messages) {

      if(!$connection || mysqli_connect_errno()) {
         $error_message = "";
   
         if($connection)
            $error_message = "<span class=\"error\">Errore</span>: " . mysqli_error($connection);
   
         array_push($error_messages, "Si &egrave; verificato un errore nella connessione al DB. $error_message");
         return false;
      }

      if(empty($pizzas_quantities)) {
         array_push($error_messages, "Errore: nessuna quantit&agrave; di pizza da aggiornare"); //for debug
         return false;
      }

      $query = "UPDATE `pizzasporto`.`pizze`
                   SET `qty` = `qty` - ?          
                 WHERE `pizze`.`id` = ? ";
   
      $statement = mysqli_prepare($connection, $query);

      if(!$statement || mysqli_stmt_errno($statement)) {
         $error_message = "";
   
         if($statement)
            $error_message = "<span class=\"error\">Errore</span>: " . mysqli_stmt_error($statement);
   
         array_push($error_messages, "Si &egrave; verificato un errore nella costruzione della 
            query al DB. $error_message");
         return false;
      }

      /* eseguo un ciclo, cos' da riutilizzare sempre lo stesso prepared statement, ma effettuando
         di volta in volta una query diversa */

      foreach($pizzas_quantities as $pizza_id => $pizza_qty_to_decrease) {

         mysqli_stmt_bind_param($statement, "ii", $pizza_qty_to_decrease, $pizza_id);

         if(mysqli_stmt_errno($statement)) {
            $error_message = "<span class=\"error\">Errore</span>: " . mysqli_stmt_error($statement);
            array_push($error_messages, "Si &egrave; verificato un errore nel processamento dei parametri. $error_message");
            return false;
         }

         mysqli_stmt_execute($statement);

         if(mysqli_stmt_errno($statement)) {
            $error_message = "<span class=\"error\">Errore</span>: " . mysqli_stmt_error($statement);
            array_push($error_messages, "Si &egrave; verificato un errore nell'esecuzione della query. $error_message");
            return false;
         }
      }

      // all pizza quantities successfully decreased here
      return true;
   }

   // ($charge is a float)
   function decrease_user_money_in_db(&$connection, &$statement, $user_nick, $charge, &$error_messages) {
      
      if(!$connection || mysqli_connect_errno()) {
         $error_message = "";
   
         if($connection)
            $error_message = "<span class=\"error\">Errore</span>: " . mysqli_error($connection);
   
         array_push($error_messages, "Si &egrave; verificato un errore nella connessione al DB. $error_message");
         return false;
      }

      $query = "UPDATE `pizzasporto`.`utenti`
                   SET `credito` = `credito` - ?          
                 WHERE `utenti`.`username` = ? ";
   
      $statement = mysqli_prepare($connection, $query);

      if(!$statement || mysqli_stmt_errno($statement)) {
         $error_message = "";
   
         if($statement)
            $error_message = "<span class=\"error\">Errore</span>: " . mysqli_stmt_error($statement);
   
         array_push($error_messages, "Si &egrave; verificato un errore nella costruzione della 
            query al DB. $error_message");
         return false;
      }

      $charge_cents = intval($charge * 100.0); //from X.XX (float) to XXX (int) - (from euros to cents)

      mysqli_stmt_bind_param($statement, "is", $charge_cents, $user_nick);
      
      if(mysqli_stmt_errno($statement)) {
         $error_message = "<span class=\"error\">Errore</span>: " . mysqli_stmt_error($statement);
         array_push($error_messages, "Si &egrave; verificato un errore nel processamento dei parametri. $error_message");
         return false;
      }

      mysqli_stmt_execute($statement);

      if(mysqli_stmt_errno($statement)) {
         $error_message = "<span class=\"error\">Errore</span>: " . mysqli_stmt_error($statement);
         array_push($error_messages, "Si &egrave; verificato un errore nell'esecuzione della query. $error_message");
         return false;
      }

      // user money successfully decreased here
      return true;
   }
?>