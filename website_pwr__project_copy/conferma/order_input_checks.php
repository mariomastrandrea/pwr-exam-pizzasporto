<?php 
   $input_error_occurred = false;

   if($error_occurred) return;

   // $available_menu_pizzas contains all pizzas that can be ordered

   require "../check_utilities.php";

   $error_messages = array();
   $pizzas_shopping_cart = array();

   foreach($_POST as $key => $value) {

      if(!preg_match("/^pizza-([\d]+)-qty$/", $key, $matches)) continue; //filter only pizza order variables

      // string -> int conversion for pizza id
      $pizza_id = intval($matches[1]);    // $matches[1] is certainly an int
      $pizza_qty = $value;

      if(!array_key_exists($pizza_id, $available_menu_pizzas)) {
         array_push($error_messages, "<span class=\"error\">Errore</span>: non esiste una pizza ordinabile
            con id = <em class=\"weight\">$pizza_id</em>");
         continue;
      }

      //here pizza id is ok

      /* riutilizzo il metodo check_pizza_qty(), per controllare che il valore passato sia effettivamente un intero valido per la quantità */
      $is_qty_valid = check_pizza_qty($pizza_qty, $error_messages);
      
      if(!$is_qty_valid) continue;

      $pizza_qty = intval($pizza_qty);
      
      //here pizza id and pizza qty are valid
      
      //check if qty is too much
      if($pizza_qty > $available_menu_pizzas[$pizza_id]["qty"]) {
         array_push($error_messages, "<span class=\"error\">Errore</span>: quantit&agrave; 
            richiesta(<em class=\"weight\">$pizza_qty</em>) troppo grande per la pizza 
            <em class=\"weight\">{$available_menu_pizzas[$pizza_id]['name']}</em>. Massima quantit&agrave;
            disponibile: <em class=\"weight\">{$available_menu_pizzas[$pizza_id]['qty']}</em>");
         continue;
      }

      if($pizza_qty > 0) {    //add to shopping cart
         $pizzas_shopping_cart[$pizza_id]["qty"] = $pizza_qty;
      }
   }

   if($input_error_occurred = count($error_messages) > 0) return;

   if(empty($pizzas_shopping_cart)) {
      $input_error_occurred = true;
      array_push($error_messages, "<span class=\"error\">Errore</span>: non è stata selezionata alcuna pizza
         da ordinare! Seleziona almeno una pizza dal menù della pagina <span class=\"page-name\">ORDINA</span>");
      return;
   }

   //order input ok here -> check user money

   $total_price = 0.0;

   foreach(array_keys($pizzas_shopping_cart) as $pizza_id) {  /* itero solo sulle chiavi, così da poter modificare il contenuto dell'array durante il ciclo */ 
      
      $subtotal = floatval($pizzas_shopping_cart[$pizza_id]["qty"]) * $available_menu_pizzas[$pizza_id]["price"];
      $pizzas_shopping_cart[$pizza_id]["subtotal"] = $subtotal;

      $total_price += $subtotal;
   }

   if($_SESSION["logged-in-user"]["money"] < $total_price) { //insufficient credit
      $user_money = number_format($_SESSION["logged-in-user"]["money"], 2);
      $total_price = number_format($total_price, 2);

      $input_error_occurred = true;
      array_push($error_messages, "<span class=\"error\">Errore</span>: non hai 
         credito sufficiente ad effettuare questo ordine! Il costo totale dell'ordine &egrave;:
         <span class=\"no-wrap\"><em class=\"weight\">&euro; $total_price</em>;</span> mentre il tuo credito &egrave;: 
         <span class=\"no-wrap\"><span class=\"red-error\">&euro; $user_money</span>!</span>
         Prova ad ordinare meno pizze.");
      return;
   }

   // it's all ok here -> save data in $_SESSION

   $_SESSION["total-price"] = $total_price;  //saving total price

   foreach($pizzas_shopping_cart as $pizza_id => $pizza_info)  //saving items quantities
      $_SESSION["pizzas-shopping-cart"][$pizza_id] = $pizza_info["qty"]; 
?>