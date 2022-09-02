<?php 
   /* in tutte le funzioni di controllo passo il vettore degli errori by reference 
   (per modificarne il contenuto e usufruirne all'esterno della funzione stessa) */

   // * users checks *

   function check_name_or_surname($name, $field_name, $min_length, $max_length, &$error_messages) {

      $general_error_statement = "Inserire un campo &quot;$field_name&quot; che sia lungo almeno 
         $min_length caratteri, ma non pi&ugrave; di $max_length caratteri, e che contenga solo 
         lettere o spazi(1 per volta)";

      if($name === "") {
         array_push($error_messages, "<span class=\"error\">Errore</span>: il campo 
            &quot;<span class=\"field-name\">$field_name</span>&quot; non pu&ograve; essere vuoto. 
            $general_error_statement");
         return false;
      }

      if(strlen($name) < $min_length) {
         array_push($error_messages, "<span class=\"error\">Errore</span>: il campo 
            &quot;<span class=\"field-name\">$field_name</span>&quot; &egrave; troppo corto. $general_error_statement");
         return false;
      }

      if(strlen($name) > $max_length) {
         array_push($error_messages, "<span class=\"error\">Errore</span>: il campo 
            &quot;<span class=\"field-name\">$field_name</span>&quot; &egrave; troppo lungo. $general_error_statement");
         return false;
      }

      /* se sono presenti caratteri al di fuori di lettere o spazi, li faccio presente -> errore */
      if(preg_match_all("/[^a-zA-Z ]/", $name, $matches)) { 
         $not_allowed_chars = $matches[0];
         $not_allowed_chars = array_map('char_quote_wrap', $not_allowed_chars);  /* metto gli apici singoli a ciascun carattere dell'array, con un callback (vedi funzione 'char_quote_wrap()' in 'pages_utilities.php')*/

         $unique_not_allowed_chars = array();

         foreach($not_allowed_chars as $char)   /* rimuovo i duplicati */
            if(!in_array($char, $unique_not_allowed_chars))
               array_push($unique_not_allowed_chars, $char);
         
         $unique_not_allowed_chars = join(", ", $unique_not_allowed_chars);

         array_push($error_messages, "<span class=\"error\">Errore</span>: il campo 
            &quot;<span class=\"field-name\">$field_name</span>&quot; contiene i seguenti 
            caratteri non ammessi $unique_not_allowed_chars. $general_error_statement");
         return false;
      }
      
      // $name has only letters and whitespaces here
      
      if(preg_match("/[ ]{2,}/", $name)) {
         array_push($error_messages, "<span class=\"error\">Errore</span>: il campo 
            &quot;<span class=\"field-name\">$field_name</span>&quot; contiene una sequenza di 
            spazi troppo lunga. $general_error_statement");
         return false;
      }

      // $name ok here
      return true;
   }

   function check_birthdate($birthdate, &$error_messages) {

      $general_error_statement = "Inserire una data di nascita valida nel formato 'aaaa-mm-gg' 
         ('0' come prima cifra pu&ograve; essere omesso)";

      if($birthdate === "") {
         array_push($error_messages, "<span class=\"error\">Errore</span>: il campo 
            &quot;<span class=\"field-name\">Data di nascita</span>&quot; non può essere vuoto. 
            $general_error_statement");
         return false;
      }

      if(preg_match_all("/[^\d\-]/", $birthdate, $matches)) { 
         $not_allowed_chars = $matches[0];
         $not_allowed_chars = array_map('char_quote_wrap', $not_allowed_chars);  /* metto gli apici singoli */
   
         $unique_not_allowed_chars = array();
   
         foreach($not_allowed_chars as $char)
            if(!in_array($char, $unique_not_allowed_chars))
               array_push($unique_not_allowed_chars, $char);
            
         $unique_not_allowed_chars = join(", ", $unique_not_allowed_chars);
   
         array_push($error_messages, "<span class=\"error\">Errore</span>: il campo 
            &quot;<span class=\"field-name\">Data di nascita</span>&quot; contiene i seguenti 
            caratteri non ammessi $unique_not_allowed_chars. $general_error_statement");
         return false;
      }

      /* controllo che sia una data nel formato richiesto dalle specifiche */
      if(!preg_match("/^([\d]{4})-([\d]{1,2})-([\d]{1,2})$/", $birthdate, $date_matches)) {  
         array_push($error_messages, "<span class=\"error\">Errore</span>: il campo 
            &quot;<span class=\"field-name\">Data di nascita</span>&quot; non &egrave; 
            nel formato corretto. $general_error_statement");
         return false;
      }

      $year = intval($date_matches[1]);
      $month = intval($date_matches[2]);
      $day_of_month = intval($date_matches[3]);

      $is_a_valid_date = checkdate($month, $day_of_month, $year);

      if(!$is_a_valid_date) {
         array_push($error_messages, "<span class=\"error\">Errore</span>: &quot;$birthdate&quot; 
            non &egrave; una data valida per il campo &quot;<span class=\"field-name\">Data di nascita</span>&quot;. 
            $general_error_statement");
         return false;
      }

      $birthdate_time = mktime(null, null, null, $month, $day_of_month, $year);

      if($birthdate_time >= time()) {
         array_push($error_messages, "<span class=\"error\">Errore</span>: campo 
            &quot;<span class=\"field-name\">Data di nascita</span>&quot; non valido. 
            Impossibile inserire una data($birthdate) successiva a quella odierna. $general_error_statement");
         return false;
      }

      // $birthdate is ok here
      return true;
   }

   function check_address($address, &$error_messages) {

      $general_error_statement = "Inserire un indirizzo di domicilio del tipo 
         <span class=\"no-wrap\">&quot;<em class=\"oblique\">(Via/Corso/Largo/Piazza/Vicolo)</span> nome 
         numeroCivico</em>&quot;, con &apos;<em class=\"oblique\">nome</em>&apos; contenente solo lettere e 
         spazi(1 per volta), e &apos;<em class=\"oblique\">numeroCivico</em>&apos; numero intero valido da 1 a 4 cifre";

      if($address === "") {
         array_push($error_messages, "<span class=\"error\">Errore</span>: il campo 
            &quot;<span class=\"field-name\">Domicilio</span>&quot; non pu&ograve; essere vuoto. 
            $general_error_statement");
         return false;
      }

      /* controllo che l'indirizzo sia nel formato richiesto, e memorizzo i suoi campi in un array */
      if(!preg_match("/^(Via|Corso|Largo|Piazza|Vicolo) ([a-zA-Z ]+) ([\d]+)$/", $address, $matches)) {
         array_push($error_messages, "<span class=\"error\">Errore</span>: il campo 
            &quot;<span class=\"field-name\">Domicilio</span>&quot; (<em class=\"weight\">&quot;$address&quot;</em>) 
            non &egrave; nel formato corretto. $general_error_statement");
         return false;
      }

      $address_name = $matches[2];
      $address_number = $matches[3];

      if(trim($address_name) === "") {
         array_push($error_messages, "<span class=\"error\">Errore</span>: il campo 
            &quot;<span class=\"field-name\">Domicilio</span>&quot; (<em class=\"weight\">&quot;$address&quot;</em>) 
            non &egrave; nel formato corretto. $general_error_statement");
         return false;
      }

      /* controllo che la parte del 'nome' non inizi o non finisca con uno spazio, e che non contenga 2 spazi consecutivi */
      if(preg_match("/[ ]{2,}/", $address_name)) {
            array_push($error_messages, "<span class=\"error\">Errore</span>: il campo 
               &quot;<span class=\"field-name\">Domicilio</span>&quot; non pu&ograve; contenere 
               2 spazi consecutivi. $general_error_statement");
         return false;
      }

      if(strlen($address_number) > 4) {
         array_push($error_messages, "<span class=\"error\">Errore</span>: il numero civico del campo 
            &quot;<span class=\"field-name\">Domicilio</span>&quot; non pu&ograve; contenere 
            pi&ugrave; di 4 cifre. $general_error_statement");
         return false;
      }

      $int_address_number = intval($address_number);

      /* utile a scartare valori interi non validi che cominciano con lo zero */
      if(strval($int_address_number) !== $address_number) {
         array_push($error_messages, "<span class=\"error\">Errore</span>: il numero civico del campo 
            &quot;<span class=\"field-name\">Domicilio</span>&quot; non &grave; un numero 
            intero valido. $general_error_statement");
         return false;
      }

      // $address ok here
      return true;
   }

   function check_money($money, &$error_messages) { 
      // MySQL smallint is on 16 bits -> max value = 2^16 - 1 = 65535 -> max money = 655.35
      return check_general_money($money, "Credito", 655.35, $error_messages);
   }

   function check_general_money($money, $field_name, $max_value, &$error_messages) {

      $general_error_statement = "Inserire un valore numerico positivo con 2 cifre decimali per il campo
         &quot;$field_name&quot;, con &apos;.&apos; come separatore dei decimali";

      if($money === "") {
         array_push($error_messages, "<span class=\"error\">Errore</span>: il campo 
            &quot;<span class=\"field-name\">$field_name</span>&quot; non pu&ograve; essere vuoto. 
            $general_error_statement");
         return false;
      }

      if(preg_match_all("/[^\d.]/", $money, $matches)) {

         $not_allowed_chars = $matches[0];
         $not_allowed_chars = array_map('char_quote_wrap', $not_allowed_chars);

         $unique_not_allowed_chars = array();

         foreach($not_allowed_chars as $char)
            if(!in_array($char, $unique_not_allowed_chars))
               array_push($unique_not_allowed_chars, $char);
         
         $unique_not_allowed_chars = join(", ", $unique_not_allowed_chars);

         array_push($error_messages, "<span class=\"error\">Errore</span>: il campo 
            &quot;<span class=\"field-name\">$field_name</span>&quot; contiene i seguenti caratteri non ammessi 
            $unique_not_allowed_chars. $general_error_statement");
         return false;
      }

      if(!preg_match("/^([\d]+)\.[\d]{2}$/", $money, $matches2)) {
         array_push($error_messages, "<span class=\"error\">Errore</span>: il campo 
            &quot;<span class=\"field-name\">$field_name</span>&quot;($money) non &egrave; nel formato corretto. 
            $general_error_statement");
         return false;
      }

      $money_int_part = $matches2[1];
      $int_money_int_part = intval($money_int_part);

      /* utile a scartare valori interi non validi che cominciano con lo zero */
      if(strval($int_money_int_part) !== $money_int_part) {
         array_push($error_messages, "<span class=\"error\">Errore</span>: &quot;$money&quot; non &egrave; 
            un valore numerico valido per il campo &quot;<span class=\"field-name\">$field_name</span>&quot;. 
            $general_error_statement");
         return false;
      }
      
      $float_money = floatval($money);

      if($float_money > $max_value)  { 
         array_push($error_messages, "<span class=\"error\">Errore</span>: &quot;$money&quot; 
            &egrave; un valore troppo alto. Il campo &quot;$field_name&quot; deve essere un valore decimale 
            inferiore o al pi&ugrave; uguale a $max_value");
         return false;
      }

      // $money is ok here
      return true;
   }

   function check_nick($nick, &$error_messages) {

      $general_error_statement = "Inserire un campo &quot;Username&quot; che sia lungo almeno 3 caratteri, 
         ma non pi&ugrave; di 8, che contenga solamente numeri(0-9), lettere(a-z,A-Z), &apos;-&apos; e &apos;_&apos;,
         e che cominci con un carattere alfabetico";

      if($nick === "") {
         array_push($error_messages, "<span class=\"error\">Errore</span>: il campo 
            &quot;<span class=\"field-name\">Username</span>&quot; non pu&ograve; essere vuoto. $general_error_statement");
         return false;
      }

      if(strlen($nick) < 3) {
         array_push($error_messages, "<span class=\"error\">Errore</span>: il campo 
            &quot;<span class=\"field-name\">Username</span>&quot; &egrave; troppo corto. $general_error_statement");
         return false;
      }

      if(strlen($nick) > 8) {
         array_push($error_messages, "<span class=\"error\">Errore</span>: il campo 
            &quot;<span class=\"field-name\">Username</span>&quot; &egrave; troppo lungo. $general_error_statement");
         return false;
      }

      if(preg_match_all("/[^\w\-]/", $nick, $matches)) {
         $not_allowed_chars = $matches[0];
         $not_allowed_chars = array_map('char_quote_wrap', $not_allowed_chars);

         $unique_not_allowed_chars = array();

         foreach($not_allowed_chars as $char)
            if(!in_array($char, $unique_not_allowed_chars))
               array_push($unique_not_allowed_chars, $char);

         $unique_not_allowed_chars = join(", ", $unique_not_allowed_chars);

         array_push($error_messages, "<span class=\"error\">Errore</span>: il campo 
            &quot;<span class=\"field-name\">Username</span>&quot; contiene i seguenti 
            caratteri non ammessi $unique_not_allowed_chars. $general_error_statement");
         return false;
      }

      $first_char = $nick[0];

      if(!preg_match("/^[a-zA-Z]$/", $first_char)) {
         array_push($error_messages, "<span class=\"error\">Errore</span>: formato campo 
            &quot;<span class=\"field-name\">Username</span>&quot; errato. Lo username deve 
            cominciare con un carattere alfabetico. $general_error_statement");
         return false;
      }

      // $nick is ok here
      return true;
   }

   function check_password($password, &$error_messages) {

      $general_error_statement = "Inserire un campo &quot;Password&quot; che sia lungo almeno 6 caratteri, ma
         non pi&ugrave; di 12, che contenga solamente numeri(0-9), lettere(a-z,A-Z) e segni di interpunzione, 
         e che contenga almeno: 1 lettera minuscola, 1 lettera maiuscola, 2 numeri e 2 segni di interpunzione";

      if($password === "") {
         array_push($error_messages, "<span class=\"error\">Errore</span>: il campo 
            &quot;<span class=\"field-name\">Password</span>&quot; 
            non pu&ograve; essere vuoto. $general_error_statement");
         return false;
      }

      if(strlen($password) < 6) {
         array_push($error_messages, "<span class=\"error\">Errore</span>: il campo 
            &quot;<span class=\"field-name\">Password</span>&quot; 
            &egrave; troppo corto. $general_error_statement");
         return false;
      }

      if(strlen($password) > 12) {
         array_push($error_messages, "<span class=\"error\">Errore</span>: il campo 
            &quot;<span class=\"field-name\">Password</span>&quot; 
            &egrave; troppo lungo. $general_error_statement");
         return false;
      }

      if(preg_match_all("/[^0-9a-zA-Z[:punct:]]/", $password, $matches)) {
         $not_allowed_chars = $matches[0];
         $not_allowed_chars = array_map('char_quote_wrap', $not_allowed_chars);

         $unique_not_allowed_chars = array();

         foreach($not_allowed_chars as $char)
            if(!in_array($char, $unique_not_allowed_chars))
               array_push($unique_not_allowed_chars, $char);

         $unique_not_allowed_chars = join(", ", $unique_not_allowed_chars);

         array_push($error_messages, "<span class=\"error\">Errore</span>: il campo 
            &quot;<span class=\"field-name\">Password</span>&quot; 
            contiene i seguenti caratteri non ammessi $unique_not_allowed_chars. $general_error_statement");
         return false;
      }

      if(!preg_match("/[a-z]/", $password)) {
         array_push($error_messages, "<span class=\"error\">Errore</span>: il campo 
            &quot;<span class=\"field-name\">Password</span>&quot; 
            non contiene alcuna lettera minuscola. $general_error_statement");
         return false;
      }

      if(!preg_match("/[A-Z]/", $password)) {
         array_push($error_messages, "<span class=\"error\">Errore</span>: il campo 
            &quot;<span class=\"field-name\">Password</span>&quot; 
            non contiene alcuna lettera maiuscola. $general_error_statement");
         return false;
      }

      $only_digits = preg_replace("/[\D]/", "", $password);

      if(strlen($only_digits) < 2) {
         array_push($error_messages, "<span class=\"error\">Errore</span>: il campo 
            &quot;<span class=\"field-name\">Password</span>&quot; 
            non contiene almeno 2 numeri. $general_error_statement");
         return false;
      }

      $only_punctuation = preg_replace("/[^[:punct:]]/", "", $password);

      if(strlen($only_punctuation) < 2) {
         array_push($error_messages, "<span class=\"error\">Errore</span>: il campo 
            &quot;<span class=\"field-name\">Password</span>&quot; non contiene almeno 
            2 segni di interpunzione. $general_error_statement");
         return false;
      }

      // $password is ok here
      return true;
   }

   // * pizzas checks *

   function parse_int_pizza_qty_to_update($new_pizza_qty_input, $name_pizza_to_update, &$error_messages) {

      if(!isset($new_pizza_qty_input) || $new_pizza_qty_input === "") {
         array_push($error_messages, "<span class=\"error\">Errore</span>: quantità da aggiornare assente per la pizza 
            &quot;$name_pizza_to_update&quot;");
         return null;
      }

      if(preg_match_all("/[\D]/", $new_pizza_qty_input, $matches)) {
         $not_allowed_chars = $matches[0];
         $not_allowed_chars = array_map('char_quote_wrap', $not_allowed_chars);

         $unique_not_allowed_chars = array();

         foreach($not_allowed_chars as $char)
            if(!in_array($char, $unique_not_allowed_chars))
               array_push($unique_not_allowed_chars, $char);

         $unique_not_allowed_chars = join(", ", $unique_not_allowed_chars);

         array_push($error_messages, "<span class=\"error\">Errore</span>: il campo 
            &quot;<span class=\"field-name\">Quantit&agrave;</span>&quot; della pizza 
            &quot;$name_pizza_to_update&quot; contiene i seguenti caratteri non ammessi 
            $unique_not_allowed_chars. Inserire un numero intero valido non negativo");
         return null;
      }

      $int_new_pizza_qty = intval($new_pizza_qty_input);

      if(strval($int_new_pizza_qty) !== $new_pizza_qty_input) {
         array_push($error_messages, "<span class=\"error\">Errore</span>: &quot;$new_pizza_qty_input&quot; 
            non &egrave; un intero valido. La quantità da aggiornare per la pizza &quot;$name_pizza_to_update&quot;
            deve essere un numero intero valido");
         return null;
      }

      if($int_new_pizza_qty > 65535) { //pizza 'qty' is a smallint -> 16 bit -> 2^16 - 1 = 65535 = max value
         array_push($error_messages, "<span class=\"error\">Errore</span>: &quot;$new_pizza_qty_input&quot; 
            &egrave; un intero troppo elevato. La quantità da aggiornare per la pizza 
            &quot;$name_pizza_to_update&quot; deve essere un numero intero minore di 65535");
         return null;
      }

      //new pizza qty ok here
      return $int_new_pizza_qty;
   }

   /* il campo "nome", nella tabella `pizze` di MySQL, è di tipo VARCHAR(32)  */
   function check_pizza_name($name, &$error_messages) { 
      return check_general_string($name, "Nome", 32, $error_messages);
   }

   /* il campo "ingredienti", nella tabella `pizze` di MySQL, è di tipo VARCHAR(60)  */
   function check_pizza_ingredients($ingredients, &$error_messages) {
      return check_general_string($ingredients, "Ingredienti", 60, $error_messages);
   }

   function check_general_string($input, $field_name, $max_length, &$error_messages) {

      $general_error_statement = "Inserire un campo &quot;$field_name&quot; non vuoto, e 
         che non superi i $max_length caratteri";

      if($input === "") {
         array_push($error_messages, "<span class=\"error\">Errore</span>: il campo 
            &quot;<span class=\"field-name\">$field_name</span>&quot; non pu&ograve; essere vuoto. 
            $general_error_statement");
         return false;
      }

      if(strlen($input) > $max_length) {   
         array_push($error_messages, "<span class=\"error\">Errore</span>: il campo 
            &quot;<span class=\"field-name\">$field_name</span>&quot; &egrave; troppo lungo. $general_error_statement");
         return false;
      }

      if(preg_match("/[ ]{2,}/", $input)) {
         array_push($error_messages, "<span class=\"error\">Errore</span>: il campo 
            &quot;<span class=\"field-name\">$field_name</span>&quot; contiene una sequenza di 
            spazi troppo lunga. $general_error_statement");
         return false;
      }

      //input string ok here
      return true;
   }

   function check_pizza_type($type, &$error_messages) {

      $general_error_statement = "Selezionare un campo &quot;Tipo&quot; che sia &quot;veggy&quot;,
         &quot;vegan&quot; o &quot;-&quot;";
      
      if($type === "") {
         array_push($error_messages, "<span class=\"error\">Errore</span>: il campo 
            &quot;<span class=\"field-name\">Tipo</span>&quot; non pu&ograve; essere vuoto. 
            $general_error_statement");
         return false;
      }

      if($type !== "-" && $type !== "veggy" && $type !== "vegan") {
         array_push($error_messages, "<span class=\"error\">Errore</span>: il campo 
            &quot;<span class=\"field-name\">Tipo</span>&quot; non ammette il valore &quot;$type&quot;. 
            $general_error_statement");
         return false;
      }

      //here $type is "-" or "veggy" or "vegan"
      return true;
   }

   function check_pizza_price($price, &$error_messages) {
      // pizza price is an INT in MySQL (32 bit -> 2^32 - 1 = 4,294,967,295 max value), but here 
      // we set max value to an arbitrary value of 999,999,999 (€ 9,999,999.99): we hope that it will be enough :)
      return check_general_money($price, "Prezzo", 999999999, $error_messages);
   }

   function check_pizza_qty($qty, &$error_messages) {

      $general_error_statement = "Inserire un numero intero valido non negativo per il 
         campo &quot;Quantit&agrave;&quot;";
      
      if($qty === "") {
         array_push($error_messages, "<span class=\"error\">Errore</span>: il campo 
            &quot;<span class=\"field-name\">Quantit&agrave;</span>&quot; non pu&ograve;
            essere vuoto. $general_error_statement");
         return false;
      }

      if(preg_match_all("/[\D]/", $qty, $matches)) {
         $not_allowed_chars = $matches[0];
         $not_allowed_chars = array_map('char_quote_wrap', $not_allowed_chars);

         $unique_not_allowed_chars = array();

         foreach($not_allowed_chars as $char)
            if(!in_array($char, $unique_not_allowed_chars))
               array_push($unique_not_allowed_chars, $char);

         $unique_not_allowed_chars = join(", ", $unique_not_allowed_chars);

         array_push($error_messages, "<span class=\"error\">Errore</span>: il campo 
            &quot;<span class=\"field-name\">Quantit&agrave;</span>&quot; contiene i seguenti 
            caratteri non ammessi $unique_not_allowed_chars. $general_error_statement");
         return false;
      }

      $int_qty = intval($qty);

      if(strval($int_qty) !== $qty) {
         array_push($error_messages, "<span class=\"error\">Errore</span>: &quot;$qty&quot; non &egrave;
            un intero valido per il campo &quot;<span class=\"field-name\">Quantit&agrave;</span>&quot;. 
            $general_error_statement");
         return false;
      }

      if($int_qty > 65535) { //pizza 'qty' is a smallint -> 16 bit -> 2^16 - 1 = 65535 = max value
         array_push($error_messages, "<span class=\"error\">Errore</span>: &quot;$int_qty&quot; &egrave; un intero
            troppo elevato per il campo &quot;<span class=\"field-name\">Quantit&agrave;</span>&quot;. Inserire un 
            numero intero valido non negativo e minore di 65535 per il campo &quot;Quantit&agrave;&quot;");
         return false;
      }

      // pizza qty ok here
      return true;
   }

   /* delivery */

   function check_delivery_hour($delivery_hour, &$error_messages) {

      $general_error_statement = "<br> Inserire un campo &quot;Orario di consegna&quot; che sia un orario valido 
         del tipo <em class=\"weight\">&quot;HH:MM&quot;</em>, e almeno 45 minuti dopo l'orario attuale.
         Ricorda che la pizzeria effettua consegne solo nel giorno corrente, dalle ore <em class=\"weight\">12:00</em> 
         alle ore <em class=\"weight\">23:59</em> (incluse)";

      if($delivery_hour === "") {
         array_push($error_messages, "<span class=\"error\">Errore</span>: il campo 
            &quot;<span class=\"field-name\">Orario di consegna</span>&quot; &egrave; 
            vuoto o ha un formato errato. $general_error_statement");
         /* il tag <input type="time"> presente nel form della pagina CONFERMA, restituisce una stringa
            vuota nel caso di input errato, per cui non posso distinguere da una stringa vuota se sia 
            stato effettivamente un input vuoto o errato */ 
         return false;
      }

      if(preg_match_all("/[^\d:]/", $delivery_hour, $matches)) {  /* i soli caratteri ammessi in questo campo sono le cifre e i ':' */
         $not_allowed_chars = $matches[0];
         $not_allowed_chars = array_map('char_quote_wrap', $not_allowed_chars);

         $unique_not_allowed_chars = array();

         foreach($not_allowed_chars as $char)
            if(!in_array($char, $unique_not_allowed_chars))
               array_push($unique_not_allowed_chars, $char);

         $unique_not_allowed_chars = join(", ", $unique_not_allowed_chars);

         array_push($error_messages, "<span class=\"error\">Errore</span>: il campo 
            &quot;<span class=\"field-name\">Orario di consegna</span>&quot; contiene i seguenti 
            caratteri non ammessi $unique_not_allowed_chars. $general_error_statement");
         return false;
      }

      if(!preg_match("/^([\d]{2}):([\d]{2})$/", $delivery_hour, $hour_matches)) {
         array_push($error_messages, "<span class=\"error\">Errore</span>: il campo 
         &quot;<span class=\"field-name\">Orario di consegna</span>&quot; presenta un formato 
         errato (<em class=\"weight\">$delivery_hour</em>). $general_error_statement");
         return false;
      }

      $int_hour = intval($hour_matches[1]);
      $int_minutes = intval($hour_matches[2]);

      if($int_hour > 23 || $int_minutes > 59) {
         array_push($error_messages, "<span class=\"error\">Errore</span>: l'input 
         <em class=\"weight\">&quot;$delivery_hour&quot;</em> inserito nel campo 
         &quot;<span class=\"field-name\">Orario di consegna</span>&quot; non
         rappresenta un orario valido. $general_error_statement");
         return false;
      }

      //hour and minutes format ok here

      // (1) check opening and closing hours (12:00 - 23:59)
      if($int_hour < 12) {
         array_push($error_messages, "<span class=\"error\">Errore</span>: l'orario 
         <em class=\"weight\">&quot;$delivery_hour&quot;</em> inserito nel campo 
         &quot;<span class=\"field-name\">Orario di consegna</span>&quot; non
         rientra nell'orario di attivit&agrave; della pizzeria. $general_error_statement");
         return false;
      }

      // (2) check delivery hour on the current day
      $delivery_time = mktime($int_hour, $int_minutes);
      $current_time = time();

      if($delivery_time < $current_time) {
         $current_time = date("H:i", $current_time);
         array_push($error_messages, "<span class=\"error\">Errore</span>: l'orario 
         <em class=\"weight\">&quot;$delivery_hour&quot;</em> inserito nel campo 
         &quot;<span class=\"field-name\">Orario di consegna</span>&quot; è un orario precedente
         a quello corrente(<em class=\"weight\">$current_time</em>). $general_error_statement");
         return false;
      }
      
      // (3) check delivery hour after 45 minutes 
      $minimum_wait_minutes = 45;
      $min_allowed_delivery_time = $current_time + $minimum_wait_minutes * 60; //add 45 minutes to current time

      if($delivery_time < $min_allowed_delivery_time) {
         $min_allowed_delivery_time = date("H:i", $min_allowed_delivery_time);
         array_push($error_messages, "<span class=\"error\">Errore</span>: l'orario 
         <em class=\"weight\">&quot;$delivery_hour&quot;</em> inserito nel campo 
         &quot;<span class=\"field-name\">Orario di consegna</span>&quot; è troppo imminente. 
         Deve essere almeno tra 45 minuti (primo orario disponibile: 
         <em class=\"weight\">$min_allowed_delivery_time</em>). $general_error_statement");
         return false;
      }

      //delivery hour ok here
      return true;
   }
?>
