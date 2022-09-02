<div class="main-box" id="sign-in-box">

   <form id="sign-in-form" method="post" action="<?= $_SERVER["PHP_SELF"] ?>"
      onreset="resetFields('username', 'password', 'general-error-box')"
      onsubmit="return checkInputForm('username', 'password', 'general-error-box')">
      <?php /* per disfunzioni sull'utilizzo del form, vedi: "../AAA_info_per_i_docenti.txt", 10) */ ?>

      <main id="user-info-box">
         <div class="input-box" id="username-input-box">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" maxlength="20" placeholder="username"
               value="<?= $last_correct_username ?>" 
               <?= !isset($is_username_ok) ? "" : ($is_username_ok ? "class=\"ok\"" : "class=\"error\"") ?>
               oninput="checkFieldOnInput('username', 'general-error-box')"
               onchange="checkFieldOnChange('username', 'general-error-box')">
            <div class="input-error-box" id="username-input-error-box"></div>
         </div>
         <div class="input-box" id="password-input-box">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" maxlength="20" placeholder="password"
               autocomplete="off" value="<?= $last_correct_password ?>" 
               <?= !isset($is_password_ok) ? "" : ($is_password_ok ? "class=\"ok\"" : "class=\"error\"") ?>
               oninput="checkFieldOnInput('password', 'general-error-box')"
               onchange="checkFieldOnChange('password', 'general-error-box')">
            <div class="input-error-box" id="password-input-error-box"></div>
         </div>
      </main>
      
      <div class="buttons-row">
         <button type="reset" class="reset-button">PULISCI</button>
         <button type="submit" class="sign-in-button">OK</button>
      </div>
   </form>

   <div id="general-error-box"></div>

</div>

<?php 
   if($_SERVER["REQUEST_METHOD"] !== "POST") return; 
      
   if(!$input_error_occurred) { 
      /* output superfluo in caso di redirect dopo il login */
      ?>

      <div class="main-box" id="login-check-output-box">
         <p class="correct-login">Login effettuato con username e password corretti</p>
         <div class="buttons-row">
            <a href="../info/info.php" class="button-style">Vai al men&ugrave; delle pizze</a>
         </div> 
      </div>  <?php
      return;
   }
   // input error occurred
   $error_messages = array_map('error_li_wrap', $error_messages);
   $error_messages_list = join("\n", $error_messages); ?>

   <div class="main-box" id="login-check-output-box">
      <ul class="error-list">
         <?= $error_messages_list ?>
      </ul>
   </div>   <?php
?>
