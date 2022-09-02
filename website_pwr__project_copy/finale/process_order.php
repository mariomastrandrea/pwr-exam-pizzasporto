<?php 
   if(!empty($input_error_occurred)) return;

   // all input ok -> process order

   require "../db_utilities.php";

   // (1) retrieve data from $_SESSION
   $pizza_quantities = $_SESSION["pizzas-shopping-cart"];   // $key = pizza_id (int) / $value = pizza_qty (int)
   $total_price = $_SESSION["total-price"];

   // (2) unset pizza quantities & total price session variables
   foreach(array_keys($_SESSION["pizzas-shopping-cart"]) as $key)
      unset($_SESSION["pizzas-shopping-cart"][$key]);
   
   unset($_SESSION["pizzas-shopping-cart"]);
   unset($_SESSION["total-price"]);

   // (3) decrease pizzas quantities in db
   
   // * togliere la porta 8889 e modificare l'indirizzo del server !!! *
   $connection = mysqli_connect("localhost", "uStrong", "SuperPippo!!!", "pizzasporto", 8889);
   $statement = null;

   $quantities_successfully_decreased = 
         decrease_pizzas_quantities_in_db($connection, $statement, $pizza_quantities, $error_messages);

   if($quantities_successfully_decreased) {
      mysqli_stmt_close($statement);

      // (4) decrease user money in db 
      $user_nick = $_SESSION["logged-in-user"]["username"];

      $user_money_successfully_decreased = 
         decrease_user_money_in_db($connection, $statement, $user_nick, $total_price, $error_messages);
      
      if($user_money_successfully_decreased) {
         // (5) change user money SESSION variable
         $_SESSION["logged-in-user"]["money"] -= $total_price;
      }
   }

   if($statement) mysqli_stmt_close($statement);
   if($connection) mysqli_close($connection);

   $input_error_occurred = count($error_messages) > 0;
?>