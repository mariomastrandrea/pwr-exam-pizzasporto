
<form id="add-new-pizza-form" method="post" action="<?= $_SERVER["PHP_SELF"] ?>"
   onreset="resetFields('pizza-name', 'pizza-ingredients', 'pizza-price', 'pizza-qty', 'error-messages-box')"
   onsubmit="return checkInputForm('pizza-name', 'pizza-ingredients', 'pizza-price', 'pizza-qty', 'error-messages-box')">

   <div class="input-box" id="pizza-name-input-box">
      <label for="pizza-name">Nome</label>
      <input type="text" id="pizza-name" name="pizza-name" placeholder="Margherita" maxlength="32"
         oninput="checkFieldOnInput('pizza-name', 'error-messages-box')"
         onchange="checkFieldOnChange('pizza-name', 'error-messages-box')">
      <div class="input-error-box" id="pizza-name-input-error-box"></div>
   </div>

   <div class="input-box" id="pizza-ingredients-input-box">
      <label for="pizza-ingredients">Ingredienti</label>
      <input type="text" id="pizza-ingredients" name="pizza-ingredients" 
         placeholder="pomodoro, mozzarella..." maxlength="60"
         oninput="checkFieldOnInput('pizza-ingredients', 'error-messages-box')"
         onchange="checkFieldOnChange('pizza-ingredients', 'error-messages-box')">
      <div class="input-error-box" id="pizza-ingredients-input-error-box"></div>
   </div>

   <div class="input-box" id="pizza-type-input-box">
      <div id="pizza-type-radio-title">Tipo</div>

      <div class="radio-choices-box">
         <div class="radio-wrapper">
            <input type="radio" name="pizza-type" id="no-pizza-type" value="-" checked>
            <label for="no-pizza-type"> - </label>
         </div>
         <div class="radio-wrapper">
            <input type="radio" name="pizza-type" id="veggy-pizza-type" value="veggy">
            <label for="veggy-pizza-type">veggy</label>
         </div>
         <div class="radio-wrapper">
            <input type="radio" name="pizza-type" id="vegan-pizza-type" value="vegan">
            <label for="vegan-pizza-type">vegan</label>
         </div>
      </div>
   </div>

   <div class="input-box" id="pizza-price-input-box">
      <label for="pizza-price">Prezzo</label>
      <div class="money-wrapper">
         <span>&euro;</span>
         <input type="text" id="pizza-price" name="pizza-price" placeholder="0.00" maxlength="10"
            oninput="checkFieldOnInput('pizza-price', 'error-messages-box')"
            onchange="checkFieldOnChange('pizza-price', 'error-messages-box')">
      </div>
      <div class="input-error-box" id="pizza-price-input-error-box"></div>
   </div>

   <div class="input-box" id="pizza-qty-input-box">
      <label for="pizza-qty">Quantit&agrave;</label>
      <input type="text" id="pizza-qty" name="pizza-qty" placeholder="0" maxlength="5"
         oninput="checkFieldOnInput('pizza-qty', 'error-messages-box')"
         onchange="checkFieldOnChange('pizza-qty', 'error-messages-box')">
      <div class="input-error-box" id="pizza-qty-input-error-box"></div>
   </div>

   <div class="buttons-errors-row" id="buttons-errors-row">
      <div class="buttons-row">
         <button type="reset">Reset</button>
         <button type="submit" name="add-new-pizza">Aggiungi</button>
      </div>

      <div id="error-messages-box"></div>
   </div>

</form>

<?php 
   if($input_error_occurred && isset($_POST["add-new-pizza"])) {  //an error occurred in 2° box
      $error_messages = array_map('error_li_wrap', $error_messages);
      $error_messages_list = join("\n", $error_messages); 
      //print ?>
      
      <ul class="error-list">
         <?= $error_messages_list ?>
      </ul>  <?php
      
      return;
   }
?>

