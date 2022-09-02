<?php 
   require "../check_utilities.php";

   $username_input = trim($_POST["username"] ?? "");
   $password_input = trim($_POST["password"] ?? "");

   $useless_err_messages = array();
   $is_a_valid_username = check_nick($username_input, $useless_err_messages);
   $is_a_valid_password = check_password($password_input, $useless_err_messages);

   $error_messages = array();
   $is_username_ok = false;
   $is_password_ok = false;
   $input_error_occurred = false;

   if(!$is_a_valid_username)
      array_push($error_messages, "Errore: &quot;$username_input&quot; non &egrave; uno <span class=\"field-name\">username</span> valido. Il tuo username deve essere lungo da 3 a 8 caratteri, deve contenere solamente numeri(0-9), lettere(a-z,A-Z), &apos;-&apos; e &apos;_&apos;, e deve cominciare con un carattere alfabetico");
   
   if(!$is_a_valid_password)
      array_push($error_messages, "Errore: la <span class=\"field-name\">password</span> inserita non &egrave; valida. La tua password deve essere lunga da 6 a 12 caratteri, deve contenere solamente numeri(0-9), lettere(a-z,A-Z) e segni di interpunzione, e deve contenere almeno: 1 lettera minuscola, 1 lettera maiuscola, 2 numeri e 2 segni di interpunzione");
   
   if($input_error_occurred = count($error_messages) > 0) return;

   // username and password are valid fields here

   require "../db_utilities.php";

   $connection = mysqli_connect("localhost", "uWeak", "posso_leggere?", "pizzasporto", 8889);
   $statement = null;

   $user_info = retrieve_user_from_db($connection, $statement, $username_input, $error_messages);

   if($statement) mysqli_stmt_close($statement);
   if($connection) mysqli_close($connection);

   if($input_error_occurred = count($error_messages) > 0) return; //db error or username not found

   $is_username_ok = true;
   //username correct here -> check password 

   if($user_info["password"] !== $password_input) {   //password is not correct
      $input_error_occurred = true;
      array_push($error_messages, "Errore: <span class=\"field-name\">password</span> errata per lo 
         username <em class=\"oblique\">&quot;$username_input&quot;</em>. <br>Ritenta con una nuova password");
      return;
   }

   $is_password_ok = true;
   //both username and password correct here -> save: (1) username cookie & (2) session data

   // (1)
   $cookie_duration_seconds = 72 * 60 * 60; // 72 hours 
   setcookie("last-correct-username", $username_input, 
               time() + $cookie_duration_seconds, "", "", true, true);
   /* Il cookie è settato(nell'ordine): per essere acceduto esclusivamente nella directory corrente(poiché serve solo in 
      questa pagina) del dominio corrente, solo su connessione sicura (HTTPS) e non da script client side (http-only) */ 

   // (2)
   $_SESSION["is-user-authenticated"] = true;
   $_SESSION["logged-in-user"] = array(
                 "name" => $user_info["name"],
              "surname" => $user_info["surname"],
            "birthdate" => $user_info["birthdate"],
              "address" => $user_info["address"],
                "money" => $user_info["money"],          //float
             "username" => $user_info["username"],  
             // (don't save user's password)
      "is-user-manager" => $user_info["is-user-manager"] //bool
   );

   //update session local variables (see ../session_code.php)
   $is_user_authenticated = true;
   $is_user_manager = $user_info["is-user-manager"];
?>