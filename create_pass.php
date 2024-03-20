<form class="loginForm" action="createPass.php" method="post">
    <input 
        type="text" 
        name="newPass" 
        placeholder="write pass" 
        value="<?php echo $new = ($_POST['newPass']) ? $_POST['newPass'] : "";?>" 
    /> 
    <br/> 
    <button class="btnLogin" type="submit">Generate Pass</button>
</form>
    
<?php
    if (!empty($_POST['newPass'])) {
        $newPass = htmlentities((string) $_POST['newPass']);
        ?>
        <input 
            type="text"
            placeholder="new pass" 
            value="<?php echo password_hash($newPass, PASSWORD_DEFAULT);?>"
            style="width: 500px;" 
        /> 
        <?php
    }
?>