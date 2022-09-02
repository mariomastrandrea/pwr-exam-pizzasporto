<?php
   unset($_SESSION["updated-pizza"]);
   
   if(isset($_POST["add-new-pizza"])) return;   // POST request comes from the other form

   $id_pizza_input = $_POST["update-qty"] ?? "";

   if($id_pizza_input === "") {
      array_push($error_messages, "<span class=\"error\">Errore</span>: nessuna pizza da aggiornare specificata");
      return;
   }

   // string -> int conversion for pizza id
   if($id_pizza_input === "0")
      $id_pizza_to_update = 0;
   else
      $id_pizza_to_update = intval($id_pizza_input) ?: null; /* per 'elvis operator', vedi "../AAA_info_per_i_docenti.txt", 4) */
  
   /* controllo prima che la stringa non sia già "0", perché intval() restituisce 0 in caso di insuccesso e non mi permetterebbe di distinguere tra un errore e un vero "0" */
   
   if($id_pizza_to_update === null || !array_key_exists($id_pizza_to_update, $menu_pizzas)) {
      // this pizza id does not exist
      array_push($error_messages, "<span class=\"error\">Errore</span>: non esiste alcuna pizza con 
         id = <em class=\"weight\">\"$id_pizza_input\"</em>");
      return;
   }

   require "../check_utilities.php";
   //pizza id exists here and it is an integer

   $pizza_name = $menu_pizzas[$id_pizza_to_update]["name"];
   $menu_pizzas[$id_pizza_to_update]["error"] = true;    //temporarily

   //analyzing only the corresponding POST parameter 'pizza-{id}-qty'
   $new_pizza_qty_input = trim($_POST["pizza-$id_pizza_to_update-qty"] ?? "");
   $new_pizza_qty = parse_int_pizza_qty_to_update($new_pizza_qty_input, $pizza_name, $error_messages);

   if($new_pizza_qty === null) return;

   $old_pizza_qty = $menu_pizzas[$id_pizza_to_update]["qty"];

   if($new_pizza_qty === $old_pizza_qty) {
      $unchanged_qty_inserted = true;
      array_push($error_messages, "<span class=\"error\">Errore</span>: quantit&agrave; inserita 
         invariata per la pizza &quot;$pizza_name&quot;");
      return;
   }

   //input ok here -> qty update

   // (1) update db
   // * rimuovere la porta e cambiare l'indirizzo del server !!!*
   $connection = mysqli_connect("localhost", "uStrong", "SuperPippo!!!", "pizzasporto", 8889); 
   $statement = null;

   $is_qty_correctly_updated = update_pizza_qty_in_db($connection, $statement, 
                     $id_pizza_to_update, $new_pizza_qty, $error_messages);
   
   if($statement) mysqli_stmt_close($statement);
   if($connection) mysqli_close($connection);
   
   // check errors
   if(!$is_qty_correctly_updated || $input_error_occurred) return;

   // qty correctly updated here

   // (2) update $menu_pizzas & success message
   $menu_pizzas[$id_pizza_to_update]["qty"] = $new_pizza_qty;
   unset($menu_pizzas[$id_pizza_to_update]["error"]);
   $_SESSION["updated-pizza"][$id_pizza_to_update] = "updated"; 
   
   $success_message = "<p><span class=\"field-name\">Quantit&agrave;</span> correttamente 
      aggiornata per la pizza <em class=\"weight\">&quot;$pizza_name&quot;</em>. Nuova 
      quantit&agrave: <em class=\"weight\">$new_pizza_qty</em></p>";
   
?>