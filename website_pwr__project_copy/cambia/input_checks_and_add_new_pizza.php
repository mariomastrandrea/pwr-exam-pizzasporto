<?php 

   if(isset($_POST["update-qty"])) return;   // POST request comes from the other form

   require "../check_utilities.php";   //checks functions

   //all input string values
   $new_pizza_info = array(
             "name" => trim($_POST["pizza-name"] ?? ""),
      "ingredients" => trim($_POST["pizza-ingredients"] ?? ""),
             "type" => trim($_POST["pizza-type"] ?? ""), 
            "price" => trim($_POST["pizza-price"] ?? ""),
              "qty" => trim($_POST["pizza-qty"] ?? "")
   );

   //check all inputs

           $is_pizza_name_ok = check_pizza_name($new_pizza_info["name"], $error_messages);
   $are_pizza_ingredients_ok = check_pizza_ingredients($new_pizza_info["ingredients"], $error_messages);
           $is_pizza_type_ok = check_pizza_type($new_pizza_info["type"], $error_messages);
          $is_pizza_price_ok = check_pizza_price($new_pizza_info["price"], $error_messages);
            $is_pizza_qty_ok = check_pizza_qty($new_pizza_info["qty"], $error_messages);

   $input_error_occurred = count($error_messages) > 0;

   if($input_error_occurred) return; 

   // all input ok here 
   // -> check if pizza name already exists; if not, add new pizza

   $connection = mysqli_connect("localhost", "uStrong", "SuperPippo!!!", "pizzasporto", 8889);
   $statement = null;

   $pizza_name_already_exists = check_pizza_name_existence_in_db($connection, 
                     $statement, $new_pizza_info["name"], $error_messages);

   if($pizza_name_already_exists === false) {
      mysqli_stmt_close($statement);
      add_new_pizza_to_db($connection, $statement, $new_pizza_info, $error_messages); 
   }

   if($statement) mysqli_stmt_close($statement);
   if($connection) mysqli_close($connection);

   if($input_error_occurred = count($error_messages) > 0) return;

   $success_message = 
      "<h3>
         Nuova pizza creata correttamente 
         <img src=\"../images/green_checkmark.png\" alt=\"green checkmark icon\" class=\"inline\">
      </h3>

      <table id=\"new-pizza-info-table\">
         <tbody>
            <tr>
               <th>Nome</th>
               <td>{$new_pizza_info['name']}</td>
            </tr>
            <tr>
               <th>Ingredienti</th>
               <td>{$new_pizza_info['ingredients']}</td>
            </tr>
            <tr>
               <th>Tipo</th>
               <td>{$new_pizza_info['type']}</td>
            </tr>
            <tr>
               <th>Prezzo</th>
               <td>&euro; {$new_pizza_info['price']}</td>
            </tr>
            <tr>
               <th>Quantit&agrave;</th>
               <td>{$new_pizza_info['qty']}</td>
            </tr>
         </tbody>
      
      </table>";
?>