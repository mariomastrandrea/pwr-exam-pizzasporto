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

   //order processed successfully here -> print message
?>

<h2><span class="website-title">OrdinaPizze.si</span></h2>

<div id="order-processed-successfully-message-box">

   <p>
      Il tuo ordine <em>è stato accettato</em>! <img src="../images/tongue_smile.png" 
      alt="tongue smile icon" class="inline"> 
      Verr&agrave; recapitato alle ore <em class="weight"><?= $delivery_hour ?></em> all'indirizzo
      <span class="no-wrap"><em class="oblique"><?= $delivery_address ?></em></span>, come da te indicato
      <img src="../images/ordered_pizza.png" alt="ordered pizza icon" class="inline">
   </p>

   <p>
      Il costo dell'ordine è di <em class="weight">&euro; <?= number_format($total_price, 2) ?></em> 
      ed &egrave; stato sottratto dal tuo credito personale. <br> Il tuo nuovo 
      credito &egrave; di <em class="weight">&euro; <?= number_format($_SESSION["logged-in-user"]["money"], 2) ?></em> 
      <img src="../images/money.png" alt="money icon" class="inline money">
   </p>

   <p>
      Speriamo che il servizio sia stato di tuo gradimento, e <em>contattaci per qualsiasi problema
      o esigenza sul tuo ordine</em>. <br>
   </p>

   <p class="last-sentence">
      <em class="weight">Al prossimo acquisto</em>! 
      <img src="../images/thumbs_up.png" alt="thumbs up icon" class="inline">
   </p>

</div>

<div class="buttons-row">
   <a href="../home/home.php" class="button-style">Torna alla home</a>
</div>