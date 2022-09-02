<?php 
   require "../db_utilities.php";

   $error_messages = array();

   // !!! rimuovere la porta 8889 e cambiare l'indirizzo del server
   $connection = mysqli_connect("localhost", "uWeak", "posso_leggere?", "pizzasporto", 8889); 
   $statement = null;

   $available_menu_pizzas = retrieve_available_pizzas_from_db($connection, $statement, $error_messages);
   
   $error_occurred = count($error_messages) > 0;
   $no_available_pizzas = $error_occurred && $available_menu_pizzas !== null;

   if($statement) mysqli_stmt_close($statement);
   if($connection) mysqli_close($connection);
?>