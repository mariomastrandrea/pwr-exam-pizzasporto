<?php 
   //retrieve data from previous registration attempt
   $sign_up_info = array(
      "name" => trim($_SESSION["sign-up-info"]["name"] ?? ""),
      "surname" => trim($_SESSION["sign-up-info"]["surname"] ?? ""),
      "birthdate" => trim($_SESSION["sign-up-info"]["birthdate"] ?? ""),
      "address" => trim($_SESSION["sign-up-info"]["address"] ?? ""),
      "money" => trim($_SESSION["sign-up-info"]["money"] ?? ""),
      "nick" => trim($_SESSION["sign-up-info"]["nick"] ?? ""),
      "password" => trim($_SESSION["sign-up-info"]["password"] ?? "")
   );

   /* se tutti i campi sono corretti eccetto il Credito, e nonostante ciò l'utente è stato mandato indietro, 
      vuol dire che il credito era eccessivo */
   $too_high_money = $sign_up_info["name"] !== "" && $sign_up_info["surname"] !== "" &&
                    $sign_up_info["birthdate"] !== "" && $sign_up_info["address"] !== "" &&
                    /* ! */ $sign_up_info["money"] === ""  &&
                    $sign_up_info["nick"] !== "" && $sign_up_info["password"] !== "";
   
   /* se tutti i campi sono corretti, e nonostante ciò l'utente è stato mandato indietro, 
      vuol dire che lo username era già impegnato */
   $busy_username = $sign_up_info["name"] !== "" && $sign_up_info["surname"] !== "" &&
                    $sign_up_info["birthdate"] !== "" && $sign_up_info["address"] !== "" &&
                    $sign_up_info["money"] !== "" && $sign_up_info["nick"] !== "" && 
                    $sign_up_info["password"] !== "";
?>

<h2>Inserisci i tuoi dati</h2>

<form id="sign-up-form" method="post" action="sign_up_landing_page.php"
   onreset="resetFields('name', 'surname', 'birthdate', 'address', 'money', 'nick', 'password', 'error-messages-box')"
   onsubmit="return checkInputForm('name', 'surname', 'birthdate', 'address', 'money', 'nick', 'password', 'error-messages-box')">
   <?php /* per disfunzioni sull'utilizzo del form, vedi: "../AAA_info_per_i_docenti.txt", 10) */ ?>

   <main id="signing-up-container">

      <fieldset> <!-- il tag <fieldset> non supporta il grid-layout in Google Chrome, per cui ho messo un ulteriore <div> all'interno, per applicarlo su quest'ultimo -->
         <legend>Informazioni personali</legend>

         <div id="personal-info-grid">
            <div class="input-box" id="name-input-box">
               <label for="name">Nome</label>
               <input type="text" id="name" name="name" placeholder="Mario" maxlength="25"
                  <?= $sign_up_info["name"] === "" ? "" : "class=\"ok\"" ?> 
                  value="<?= $sign_up_info["name"] ?>"
                  oninput="checkFieldOnInput('name', 'error-messages-box')" 
                  onchange="checkFieldOnChange('name', 'error-messages-box')">
               <div class="input-error-box" id="name-input-error-box"></div>
            </div>

            <div class="input-box" id="surname-input-box">
               <label for="surname">Cognome</label>
               <input type="text" id="surname" name="surname" placeholder="Rossi" maxlength="30"
                  <?= $sign_up_info["surname"] === "" ? "" : "class=\"ok\"" ?>
                  value="<?= $sign_up_info["surname"] ?>"
                  oninput="checkFieldOnInput('surname', 'error-messages-box')" 
                  onchange="checkFieldOnChange('surname', 'error-messages-box')">
               <div class="input-error-box" id="surname-input-error-box"></div>
            </div>

            <div class="input-box" id="birthdate-input-box">
               <label for="birthdate">Data di nascita</label>
               <input type="text" id="birthdate" name="birthdate" placeholder="aaaa-mm-gg" maxlength="10"
                  <?= $sign_up_info["birthdate"] === "" ? "" : "class=\"ok\"" ?>
                  value="<?= $sign_up_info["birthdate"] ?>"
                  oninput="checkFieldOnInput('birthdate', 'error-messages-box')" 
                  onchange="checkFieldOnChange('birthdate', 'error-messages-box')">
               <div class="input-error-box" id="birthdate-input-error-box"></div>
            </div>

            <div class="input-box" id="address-input-box">
               <label for="address">Domicilio</label>
               <input type="text" id="address" name="address" placeholder="Via Roma 1"
                  <?= $sign_up_info["address"] === "" ? "" : "class=\"ok\"" ?>
                  value="<?= $sign_up_info["address"] ?>"
                  oninput="checkFieldOnInput('address', 'error-messages-box')" 
                  onchange="checkFieldOnChange('address', 'error-messages-box')">
               <div class="input-error-box" id="address-input-error-box"></div>
            </div>
         </div>
      </fieldset>

      <fieldset>  <!-- il tag <fieldset> non supporta il grid-layout in Google Chrome, per cui ho messo un ulteriore <div> all'interno, per applicarlo su quest'ultimo -->
         <legend>Dati account</legend>
         
         <div id="account-data-grid">
            <div class="input-box" id="money-input-box">
               <label for="money">Credito</label>
               <div class="money-wrapper">
                  <span>&euro;</span>
                  <input type="text" id="money" name="money" placeholder="0.00"
                     <?= $sign_up_info["money"] === "" ? ($too_high_money ? "class=\"error\"" : "") : "class=\"ok\"" ?>
                     value="<?= $sign_up_info["money"] ?>"
                     oninput="checkFieldOnInput('money', 'error-messages-box')" 
                     onchange="checkFieldOnChange('money', 'error-messages-box');">
               </div>
               <div class="input-error-box" id="money-input-error-box"></div>
            </div>

            <div class="input-box" id="nick-input-box">
               <label for="nick">Username</label>
               <input type="text" id="nick" name="nick" maxlength="8" placeholder="username"
                  <?= $sign_up_info["nick"] === "" ? "" : ($busy_username ? "class=\"error\"" : "class=\"ok\"") ?>
                  value="<?= $sign_up_info["nick"] ?>"
                  oninput="checkFieldOnInput('nick', 'error-messages-box')" 
                  onchange="checkFieldOnChange('nick', 'error-messages-box')">
               <div class="input-error-box" id="nick-input-error-box"></div>
            </div>

            <div class="input-box" id="password-input-box">
               <label for="password">Password</label>
               <input type="password" id="password" name="password" maxlength="12"  placeholder="password"
                  <?= $sign_up_info["password"] === "" ? "" : "class=\"ok\"" ?>
                  autocomplete="off" value="<?= $sign_up_info["password"] ?>"
                  oninput="checkFieldOnInput('password', 'error-messages-box')" 
                  onchange="checkFieldOnChange('password', 'error-messages-box')">
               <div class="input-error-box" id="password-input-error-box"></div>
            </div>
         </div>
      </fieldset>

   </main>

   <div class="buttons-errors-row">
      <div class="buttons-row">
         <button type="reset">Reset</button>
         <button type="submit">Registra</button>
      </div>

      <div id="error-messages-box"></div>
   </div>
   
</form>