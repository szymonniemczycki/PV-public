<html lang="pl">

	<?php 
    //show header
	require_once("templates/header.php");

	//show login form ?>
	<body class="body">

        <form class="loginForm" action="./create_pass.php" method="post">
            <input 
                id="newPass"
                type="text" 
                name="newPass" 
                placeholder="write pass" 
                value="<?php echo $new = (!empty($_POST['newPass'])) ? $_POST['newPass'] : "";?>" 
            /> 
            <br/> 
            <button class="btnLogin" type="submit">Generate Pass</button>

            <?php
            if (!empty($_POST['newPass'])) {
                $newPass = htmlentities((string) $_POST['newPass']);
                ?>
                <input 
                    id="genNewPass"
                    type="text"
                    placeholder="new pass" 
                    value="<?php echo password_hash($newPass, PASSWORD_DEFAULT);?>"
                    class="newPass"
                /> 
                <?php
            }
            ?>

        </form>
    



    </body>
</html>