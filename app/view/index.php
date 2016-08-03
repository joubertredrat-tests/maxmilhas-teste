<h2>Galerias</h2>
<ul>
<?php if ($galleries): ?>
<?php foreach ($galleries as $gallery): ?>
    <li>
        <?php echo $gallery['name']; ?> -
        <a href="<?php echo \App\Functions::getAppUrl('display/gallery/'.$gallery['id'].'/photo/1'); ?>">Fotos</a>
    </li>
<?php endforeach; ?>
<?php else: ?>
    <p>Não existe nenhuma galeria cadastrada no momento</p>
<?php endif; ?>
</ul>