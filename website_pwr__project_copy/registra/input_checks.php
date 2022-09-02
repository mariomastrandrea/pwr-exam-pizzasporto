<?php 
   require "../check_utilities.php";   //checks functions

   /* setto le proprietà dell'array ai valori passati dal form, se esistono, oppure ad una stringa vuota */

   // all input string values
   $new_user_info = array(
           "name" => trim($_POST["name"] ?? ""),
        "surname" => trim($_POST["surname"] ?? ""),
      "birthdate" => trim($_POST["birthdate"] ?? ""),
        "address" => trim($_POST["address"] ?? ""),
          "money" => trim($_POST["money"] ?? ""),
           "nick" => trim($_POST["nick"] ?? ""),
       "password" => trim($_POST["password"] ?? "")
   );
   
   // check fields
   $error_messages = array();

        $is_name_ok = check_name_or_surname($new_user_info["name"], "Nome", 2, 25, $error_messages);
     $is_surname_ok = check_name_or_surname($new_user_info["surname"], "Cognome", 2, 30, $error_messages);
   $is_birthdate_ok = check_birthdate($new_user_info["birthdate"], $error_messages);
     $is_address_ok = check_address($new_user_info["address"], $error_messages);
       $is_money_ok = check_money($new_user_info["money"], $error_messages);
        $is_nick_ok = check_nick($new_user_info["nick"], $error_messages);
    $is_password_ok = check_password($new_user_info["password"], $error_messages);

   if($active_session)
      store_in_session($is_name_ok, $is_surname_ok, $is_birthdate_ok, $is_address_ok, 
                        $is_money_ok, $is_nick_ok, $is_password_ok, $new_user_info);

   $input_error_occurred = count($error_messages) > 0;

   if($input_error_occurred) return;  
      
   //saving birthdate info

   preg_match("/^(\d+)-(\d+)-(\d+)$/", $new_user_info["birthdate"], $date_fields);

   $year = $date_fields[1];
   $month = strlen($date_fields[2]) < 2 ? "0{$date_fields[2]}" : $date_fields[2];
   $day_of_month = strlen($date_fields[3]) < 2 ? "0{$date_fields[3]}" : $date_fields[3];

   $new_user_info["birthdate-year"] = $year;
   $new_user_info["birthdate-month"] = $month;
   $new_user_info["birthdate-day-of-month"] = $day_of_month;
      
   //check if username already exists; if not, add new user

   require "../db_utilities.php";

   $connection = mysqli_connect("localhost", "uStrong", "SuperPippo!!!", "pizzasporto", 8889);
   $statement = null;

   $username_already_exists = check_username_in_db($connection, $statement, 
                                    $new_user_info["nick"], $error_messages);

   if($username_already_exists === false) {
      mysqli_stmt_close($statement);
      add_new_user_to_db($connection, $statement, $new_user_info, $error_messages); 
   }
   
   $input_error_occurred = count($error_messages) > 0;

   if($statement) mysqli_stmt_close($statement);
   if($connection) mysqli_close($connection);
?>