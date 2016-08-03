<form method="POST" enctype="multipart/form-data">
    <input type="file" name="original_filename" id="original_filename" accept="image/*" required>
    <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
    <br />
    <input type="submit" value="Enviar">
</form>