<h2>Benvenuto su <span class="website-title">OrdinaPizze.si</span> !</h2>
<p>Il portale che ti consente di visualizzare, gestire ed <em>ordinare tutte le nostre pizze interamente online</em>
   <?php for($i=0; $i<3; $i++) { ?>
      <img class="inline" src="../images/tongue_smile.png" alt="pizza icon"> <?php 
   } ?>
</p>
<p>Dopo esserti registrato, effettuando il <em>login</em> avrai accesso al tuo borsellino virtuale, 
   che ti consentirà di effettuare i tuoi ordini
   <img class="inline" src="../images/ordered_pizza.png" alt="ordered pizza icon">
</p> 
<ul id="pages-list">
   <li> <?= pizza_li_icon() ?>
      La pagina <a href="../registra/registra.php"><span class="page-name">REGISTRA</span></a> 
      ti consente di effettuare la <em>registrazione al portale</em>
   </li>
   <li> <?= pizza_li_icon() ?>
      La pagina <?= $is_user_manager ? "<a href=\"../cambia/cambia.php\"><span class=\"page-name\">CAMBIA</span></a>" : 
      "<span class=\"page-name disabled\">CAMBIA</span>" ?> , di esclusivo accesso ai <em>gestori del 
      locale</em>, consente di <em>modificare le quantit&agrave;</em> delle pizze disponibili e di 
      <em>aggiungere una nuova pizza</em> al menù
   </li>
   <li> <?= pizza_li_icon() ?>
      La pagina <a href="../info/info.php"><span class="page-name">INFO</span></a> 
      <em>elenca tutte le pizze ordinabili</em> del menù
   </li>
   <li> <?= pizza_li_icon() ?>
      La pagina <?= !$is_user_authenticated ? "<a href=\"../login/login.php\"><span class=\"page-name\">LOGIN</span></a>" : 
      "<span class=\"page-name disabled\">LOGIN</span>" ?> ti consente di 
      effettuare il <em>login</em>
   </li>
   <li> <?= pizza_li_icon() ?>
      La pagina <a href="../ordina/ordina.php"><span class="page-name">ORDINA</span></a> ti 
      consente di effettuare un <em>nuovo ordine</em>, selezionando le quantit&agrave; desiderate 
      per ciascuna tipologia di pizza
   </li>
   <li> <?= pizza_li_icon() ?>
      La pagina <?= $is_user_authenticated ? "<a href=\"{$_SERVER["PHP_SELF"]}?logout\"><span class=\"page-name\">LOGOUT</span></a>" : 
      "<span class=\"page-name disabled\">LOGOUT</span>" ?> 
      ti consente di effettuare il <em>logout</em>
   </li>
</ul>
<p>In alto a destra puoi visualizzare il tuo <em>username</em> 
   <img src="../images/<?= $is_user_authenticated ? ($is_user_manager ? "manager_user" : "user") : 
      "anonymous_user" ?>.png" alt="user icon" class="inline"> e il tuo <em>borsellino</em> 
      <img src="../images/money.png" alt="money icon" class="inline"> attuale</p>