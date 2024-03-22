<div class="login">
	<?php if (empty($_SESSION['user'])) { ?>
		<form class="loginForm" action="login.php" method="post">
			<div class="logininfo">
				<h3>Logowanie</h3>
				<p>RCE importer</p>
			</div>
			<div class="loginInputs">
				<input id="loginInput" type="text" name="login" placeholder="login" required/> 
				<input id="passInput" type="password" name="password" placeholder="password" required/>
				<input id="triedInput" type="hidden" name="tried" value="true" />
				<button class="btnLogin" type="submit">LOG IN</button>
			</div>
		</form>
	<?php } ?>
</div>