<?php 
   $active_session = session_status() === PHP_SESSION_ACTIVE;

   if(!$active_session)
      $active_session = session_start();

   $is_user_authenticated = $_SESSION["is-user-authenticated"] ?? false;
   $is_user_manager = $_SESSION["logged-in-user"]["is-user-manager"] ?? false;
?>