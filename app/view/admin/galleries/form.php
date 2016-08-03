<form method="POST">
    <label for="name"> Nome*</label>
    <input type="text" name="name" id="name" value="<?php echo $name; ?>" required>
    <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
    <br />
    <input type="submit" value="Enviar">
</form>