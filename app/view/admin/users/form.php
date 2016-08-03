<form method="POST">
    <?php if($id): ?>
        <label for="name"> Username*</label>
        <input type="text" name="username" id="username" value="<?php echo $username; ?>" disabled="disabled">
        <label for="name"> Senha</label>
        <input type="password" name="password" id="password">
    <?php else: ?>
        <label for="name"> Username*</label>
        <input type="text" name="username" id="username" required>
        <label for="name"> Senha*</label>
        <input type="password" name="password" id="password" required>
    <?php endif; ?>
    <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
    <br />
    <input type="submit" value="Enviar">
</form>