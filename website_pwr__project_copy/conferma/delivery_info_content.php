
<?php 
   date_default_timezone_set("Europe/Rome");

   $current_localtime = time();
   $formatted_current_localtime = date("H:i", $current_localtime);

   $first_allowed_delivery_localtime = $current_localtime + 45 * 60; //after 45 minutes

   $first_allowed_delivery_localtime__hours = date("H", $first_allowed_delivery_localtime);
   $first_allowed_delivery_localtime__minutes = date("i", $first_allowed_delivery_localtime);

   if(intval($first_allowed_delivery_localtime__hours) < 12) {
      $first_allowed_delivery_localtime__hours = "12";
      $first_allowed_delivery_localtime__minutes = "00";
   }

   $formatted_first_allowed_delivery_localtime = 
         "$first_allowed_delivery_localtime__hours:$first_allowed_delivery_localtime__minutes"
?>

<h3>Inserisci le informazioni sulla consegna</h3>

<!-- scelgo le 23:59 e non le 24:00 perché considero solo gli orari nel giorno corrente,
   ed un orario delle 24:00 verrebbe inserito come 00:00 dal tag <input type="time">,
   e verrebbe considerato come orario del giorno successivo -->

<div id="delivery-info-box" class="main-box">
   <form id="delivery-info-form" method="post" action="../finale/finale.php"
      onsubmit="return checkInputForm('deliveryAddress', 'deliveryHour', 'error-messages-box')">

      <div id="delivery-info-input-flex-row">
         <div class="input-box" id="deliveryAddress-input-box">
            <label for="deliveryAddress">Indirizzo di consegna</label>
            <input type="text" id="deliveryAddress" name="deliveryAddress" 
               value="<?= $_SESSION["logged-in-user"]["address"] ?>" maxlength="50"
               oninput="checkFieldOnInput('deliveryAddress','error-messages-box')"
               onchange="checkFieldOnChange('deliveryAddress','error-messages-box')">
            <div class="input-error-box" id="deliveryAddress-input-error-box"></div>
         </div>

         <div class="input-box" id="deliveryHour-input-box">
            <label for="deliveryHour">Orario di consegna *</label>
            <input type="time" id="deliveryHour" name="deliveryHour"
               value="<?= $first_allowed_delivery_localtime__hours ?>:<?= $first_allowed_delivery_localtime__minutes ?>"  
               <?php 
                  //precompilo il campo "Orario di consegna" con il primo orario disponibile,
                  //per dare un suggerimento all'utente. L'attributo placeholder, infatti, non è
                  //supportato per questo tipo di <input> 
               ?>
               oninput="checkFieldOnInput('deliveryHour','error-messages-box')"
               onchange="checkFieldOnChange('deliveryHour','error-messages-box')">
            <div class="input-error-box" id="deliveryHour-input-error-box"></div>
         </div>
      </div>

      <div class="buttons-errors-row">
         <div class="buttons-row">
            <a href="../home/home.php" class="button-style">Annulla</a>
            <button type="submit">Ok</button>
         </div>

      <div id="error-messages-box"></div>
   </div>

   </form>
</div>

<p class="notice">
   * La pizzeria effettua consegne dalle <em class="weight"><time>12:00</time></em> alle 
   <em class="weight"><time>23:59</time></em> e per motivi logistici l'orario di consegna deve
   essere <em>almeno dopo 45 minuti</em>. <br> Attualmente sono le 
   <em class="weight"><time><?= $formatted_current_localtime ?></em></time> (primo orario disponibile: 
   <em class="weight"><time><?= $formatted_first_allowed_delivery_localtime ?></time>)</em>
</p>