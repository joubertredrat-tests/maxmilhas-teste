<?php
    if(!is_null($ok) && !$ok) {
        echo 'Não foi possível conectar no banco de dados, tente novamente ou altere os valores.';
    }
?>
<form method="POST">
    <label for="db_name"> Nome do banco*</label>
    <input type="text" name="db_name" id="db_name" required>
    <br />
    <label for="db_user"> Usuário*</label>
    <input type="text" name="db_user" id="db_user" required>
    <br />
    <label for="db_password"> Senha*</label>
    <input type="password" name="db_password" id="db_password" required>
    <br />
    <label for="db_host"> Host*</label>
    <input type="text" name="db_host" id="db_host" value="localhost" required>
    <br />
    <label for="db_port"> Porta*</label>
    <input type="number" name="db_port" id="db_port" value="3306" required>    
    <br />
    <input type="submit" value="Enviar">
</form>