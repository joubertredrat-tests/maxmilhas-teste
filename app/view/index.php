<h2>Galerias</h2>
<ul>
<?php foreach ($galleries as $gallery): ?>
    <li>
        <?php echo $gallery['name']; ?> -
        <a href="<?php echo \App\Functions::getAppUrl('display/gallery/'.$gallery['id'].'/photo/1'); ?>">Fotos</a>
    </li>
<?php endforeach; ?>
</ul>