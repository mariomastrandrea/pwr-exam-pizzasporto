<?php 

   if($input_error_occurred) {

      $error_messages = array_map('error_li_wrap', $error_messages);
      $error_messages_list = join("\n", $error_messages); 
      //print ?>

         <ul class="error-list">
            <?= $error_messages_list ?>
         </ul>  
         
         <div class="buttons-row">
            <a href="../ordina/ordina.php" class="button-style">Annulla</a>
         </div>   <?php
         
      return;
   }

   //input ok -> print page
   // - $pizzas_shopping_cart contains all ordered pizzas (qty & subtotal)
   // - $available_menu_pizzas contains all pizzas info
   // - $total_price contains the order total price

   $order_recap_table_rows = array();

   foreach($pizzas_shopping_cart as $pizza_id => $pizza_order_info) {

      $pizza_price = number_format($available_menu_pizzas[$pizza_id]["price"], 2);
      $pizza_order_info["subtotal"] = number_format($pizza_order_info["subtotal"], 2);

      $row = 
         "<tr>
            <td class=\"pizza-name\">{$available_menu_pizzas[$pizza_id]['name']}</td>
            <td class=\"pizza-qty\">{$pizza_order_info['qty']}</td>    
            <td class=\"pizza-price\">&euro; $pizza_price</td>
            <td class=\"pizza-subtotal\">&euro; {$pizza_order_info['subtotal']}</td>
         </tr>";

      array_push($order_recap_table_rows, $row);
   }

   $order_recap_table_rows = join("\n", $order_recap_table_rows);

   $order_recap_table_header = 
      "<tr>
         <th>Nome</th>
         <th>Quantit&agrave;</th>
         <th>Prezzo</th>
         <th>Subtotale</th>    
      </tr>";

   $formatted_total_price = number_format($total_price, 2);

   $order_recap_table_footer = 
      "<tr>
         <th colspan=\"3\">Totale</th>
         <td>&euro; $formatted_total_price</td>
      </tr>";
   
   
//print table ?>
   
<table id="order-recap-table">
   <caption>Pizze da ordinare</caption>

   <thead>
      <?= $order_recap_table_header ?>
   </thead>
   
   <tbody>
      <?= $order_recap_table_rows ?>
   </tbody>

   <tfoot>
      <?= $order_recap_table_footer ?>
   </tfoot>
      
</table> 

<p class="money-info">Il tuo credito scenderebbe a 
   <em class="weight">&euro; <?= number_format($_SESSION["logged-in-user"]["money"] - $total_price, 2) ?></em></p>

