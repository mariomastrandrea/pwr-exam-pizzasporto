<?php

   if($input_error_occurred) {

      $error_messages = array_map('error_li_wrap', $error_messages);
      $error_messages_list = join("\n", $error_messages); ?>

      <div class="error-messages-box">
         <ul>
            <?= $error_messages_list ?>
         </ul>
      </div>
      <div class="buttons-row">
         <a class="button-style" href="registra.php">Torna alla registrazione</a>
      </div>   
               <?php
      return;
   }
   elseif($active_session)
      unset($_SESSION["sign-up-info"]);  
?>
   
<h2>Nuovo utente registrato:</h2>

<table id="new-user-info-table">
   <tbody>
      <tr>
         <th>Nome</th>
         <td><?= $new_user_info["name"] ?></td>
      </tr>
      <tr>
         <th>Cognome</th>
         <td><?= $new_user_info["surname"] ?></td>
      </tr>
      <tr>
         <th>Data di nascita</th>
         <td><time datetime="<?= $new_user_info["birthdate-year"] ?>-<?= $new_user_info["birthdate-month"] ?>-<?= $new_user_info["birthdate-day-of-month"] ?>">
            <?= $new_user_info["birthdate"] ?>
         </time></td>
      </tr>
      <tr>
         <th>Domicilio</th>
         <td><address> 
            <?= $new_user_info["address"] ?>
         </address></td>
      </tr>
      <tr>
         <th>Credito</th>
         <td>&euro; <?= $new_user_info["money"] ?></td>
      </tr>
      <tr>
         <th>Username</th>
         <td><?= $new_user_info["nick"] ?></td>
      </tr>
      <tr>
         <th>Password</th>
         <td><?= $new_user_info["password"] ?></td>
      </tr>
   </tbody>

</table>

<?php 
   if(!$is_user_authenticated) { ?>
      <div class="buttons-row">
         <a href="../login/login.php" class="button-style">Vai alla pagina di login</a>
      </div> <?php 
   }
?>
   