<?php
   
   if($error_occurred) {   //error retrieving pizzas from db

      $error_messages = array_map('error_li_wrap', $error_messages);
      $error_messages_list = join("\n", $error_messages); 
      //print ?>
      
         <ul class="error-list">
            <?= $error_messages_list ?>
         </ul>  <?php
         
      return;
   }

   $menu_table_rows = array();

   foreach($menu_pizzas as $pizza_id => $pizza_info) {

      $pizza_info["price"] = number_format($pizza_info["price"], 2);  
      $error_class = !empty($pizza_info["error"]) ? "error" : "";
      $updated_class = $_SESSION["updated-pizza"][$pizza_info["id"]] ?? "";
      $qty_input_id = "pizza-{$pizza_info['id']}-qty";

      $row = 
         "<tr>
            <td class=\"pizza-name\">{$pizza_info['name']}</td>
            <td class=\"pizza-price\"><span class=\"no-wrap\">&euro; {$pizza_info['price']}</span></td>
            <td class=\"pizza-qty $updated_class\">
               <input type=\"text\" name=\"$qty_input_id\" id=\"$qty_input_id\" 
                  maxlength=\"5\" value=\"{$pizza_info['qty']}\" class=\"$error_class\"
                  oninput=\"checkFieldOnInput('$qty_input_id', 'general-error-messages-box')\"
                  onchange=\"checkFieldOnChange('$qty_input_id', 'general-error-messages-box')\">
               <div class=\"input-error-box\" id=\"$qty_input_id-input-error-box\"></div>
            </td>
            <td>
               <button type=\"submit\" name=\"update-qty\" value=\"{$pizza_info['id']}\" class=\"button-style\" 
               onclick=\"return checkInputForm('$qty_input_id', 'general-error-messages-box')\">
               Aggiorna prodotto</button>
            </td>   
         </tr>";
      
      /* IMPORTANTE:
         per il submit di questo form, eseguo il controllo sulla quantità mediante l'event
         handler 'onclick' sul singolo bottone, anziché l'event handler 'onsubmit' sull'intero form:
         in questo modo riesco ad intercettare quale <button> è stato premuto dall'utente, e di conseguenza
         controllerò solo il campo di <input> corrispondente. Analogamente si sarebbe potuto mettere il 
         controllo solo sull'event handler 'onsubmit' del <form> ed effettuare tutte le volte tutti i controlli 
         su tutti i campi di input, quindi anche su quelli non corrispondenti al tasto pigiato
      */

      array_push($menu_table_rows, $row);
   }

   $menu_table_rows = join("\n", $menu_table_rows);

   $menu_table_header = 
      "<tr>
         <th>Nome</th>
         <th>Prezzo</th>
         <th>Quantit&agrave;</th> 
         <th>Aggiornamento</th>   
      </tr>";

//print table & form ?>

<form id="update-pizza-qty-form" method="post" action="<?= $_SERVER["PHP_SELF"] /* vedi nota sopra */?>"> 
   <table id="menu-pizzas-table">
      <caption>Men&ugrave; pizze</caption>

      <thead>
         <?= $menu_table_header ?>
      </thead>
      
      <tbody>
         <?= $menu_table_rows ?>
      </tbody>
         
   </table>

   <div id="general-error-messages-box"></div>
</form>

<?php 
   if($input_error_occurred && isset($_POST["update-qty"])) { //an error occurred in 1° box
      $error_message = join("\n", $error_messages); ?>
      
      <div class="error-messages-box">
         <p class="error-message"><?= $error_message ?></p>
      </div> <?php
   }
?>


