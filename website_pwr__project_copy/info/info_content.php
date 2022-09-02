<?php 
   if($error_occurred) {

      $error_messages = array_map('error_li_wrap', $error_messages);
      $error_messages_list = join("\n", $error_messages); 
      //print ?>

         <ul class="error-list">
            <?= $error_messages_list ?>
         </ul>  <?php
         
      return;
   }
   
   $menu_table_rows = array();

   foreach($available_menu_pizzas as $pizza_id => $pizza_info) {

      $pizza_info["price"] = number_format($pizza_info["price"], 2);
      $row = "";

      if($is_user_authenticated) {  //show qty
         $row = 
            "<tr>
               <td class=\"pizza-name\">{$pizza_info['name']}</td>
               <td class=\"pizza-ingredients\">({$pizza_info['ingredients']})</td>
               <td class=\"pizza-type\">{$pizza_info['type']}</td>
               <td class=\"pizza-qty\">{$pizza_info['qty']}</td>    
               <td class=\"pizza-price\"><span class=\"no-wrap\">&euro; {$pizza_info['price']}</span></td>
            </tr>";
      }
      else {   //not show qty
         $row = 
            "<tr>
               <td class=\"pizza-name\">{$pizza_info['name']}</td>
               <td class=\"pizza-ingredients\">({$pizza_info['ingredients']})</td>
               <td class=\"pizza-type\">{$pizza_info['type']}</td>
               <td class=\"pizza-price\"><span class=\"no-wrap\">&euro; {$pizza_info['price']}</span></td>
            </tr>";
      }

      array_push($menu_table_rows, $row);
   }

   $menu_table_rows = join("\n", $menu_table_rows);

   $menu_table_header = "";

   if($is_user_authenticated) {  //show qty
      $menu_table_header = 
         "<tr>
            <th>Nome</th>
            <th>Ingredienti</th>
            <th>Veggy/Vegan</th>
            <th>Quantit&agrave;</th>    
            <th>Prezzo</th>
         </tr>";
   }
   else {   //not show qty
      $menu_table_header = 
         "<tr>
            <th>Nome</th>
            <th>Ingredienti</th>
            <th>Veggy/Vegan</th>
            <th>Prezzo</th>
         </tr>";
   }
   
//print table ?>
   
<table id="menu-pizzas-table">
   <caption>Pizze ordinabili</caption>

   <thead>
      <?= $menu_table_header ?>
   </thead>
   
   <tbody>
      <?= $menu_table_rows ?>
   </tbody>
      
</table> 