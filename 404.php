<html lang="pl">

<?php require_once("templates/header.php"); ?>

  <body class="body">
  <section>
    <div class="errorMessage">
        <div class="infoMessage">
        Any problem with App...
        </div>
    </div>
</section>
    
    <div class="login">
      <?php if (empty($_SESSION['user'])) { ?>
        <form class="loginForm" action="login.php" method="post">
          <input type="text" name="login" placeholder="login" /> 
          <br/> 
          <input type="password" name="password" placeholder="password" /> 
          <br/>  
          <input type="hidden" name="tried" value="true" />
          <button class="btnLogin" type="submit">LOG IN</button>
        </form>
      <?php } ?>
      </div>
  </body>
</html>