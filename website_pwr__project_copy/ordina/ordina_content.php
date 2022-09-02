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
      $pizza_qty_options = array();

      /* creo tanti tag <option>, dalla quantità 0 alla quantità disponibile, e seleziono quella con 0 quantità */
      for($i = 0; $i <= $pizza_info["qty"]; $i++) {
         $option_selection = $i === 0 ? " selected" : "";
         array_push($pizza_qty_options, "<option value=\"$i\"$option_selection>$i</option>");
      }

      $pizza_qty_options = join("\n", $pizza_qty_options);

      $row = 
         "<tr>
            <td class=\"pizza-name\">{$pizza_info['name']}</td>
            <td class=\"pizza-price\">&euro; {$pizza_info['price']}</td>
            <td class=\"pizza-qty\">
               <select name=\"pizza-{$pizza_info['id']}-qty\" id=\"pizza-{$pizza_info['id']}-qty\"
                  onchange=\"checkAllPizzasQuantitiesOnChange('pizza-{$pizza_info['id']}-qty','pizza-shopping-cart-form','error-messages-box')\">
                  $pizza_qty_options
               </select>
            </td>    
         </tr>";

      array_push($menu_table_rows, $row);
   }

   $menu_table_rows = join("\n", $menu_table_rows);

   $menu_table_header = "";

   $menu_table_header = 
      "<tr>
         <th>Nome</th>
         <th>Prezzo</th>
         <th>Quantit&agrave;</th>    
      </tr>";
   
//print table ?>

<form id="pizza-shopping-cart-form" method="post" action="../conferma/conferma.php"
   onreset="resetAllQuantitiesFieldsIn('pizza-shopping-cart-form', 'error-messages-box')"
   onsubmit="return checkAllQuantitiesZerosIn('pizza-shopping-cart-form', 'error-messages-box')">

   <table id="menu-pizzas-table">
      <caption>Pizze ordinabili</caption>

      <thead>
         <?= $menu_table_header ?>
      </thead>
      
      <tbody>
         <?= $menu_table_rows ?>
      </tbody>
         
   </table> 

   <div id="error-messages-box"></div>

   <div class="buttons-row">
      <button type="reset">Annulla</button>
      <button type="submit">Procedi</button>
   </div>

</form>