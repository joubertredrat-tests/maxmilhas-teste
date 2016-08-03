<a href="<?php echo \App\Functions::getAppUrl('admin'); ?>">Voltar</a>
<h2>Galerias</h2>
<a href="<?php echo \App\Functions::getAppUrl('admin/gallery-new'); ?>">Novo</a>
<ul>
<?php if ($galleries): ?>
<?php foreach ($galleries as $gallery): ?>
    <li>
        <?php echo $gallery['name']; ?> -
        <a href="<?php echo \App\Functions::getAppUrl('admin/photo/'.$gallery['id']); ?>">Fotos</a> | 
        <a href="<?php echo \App\Functions::getAppUrl('admin/gallery-edit/'.$gallery['id']); ?>">Editar</a> | 
        <a href="<?php echo \App\Functions::getAppUrl('admin/gallery-remove/'.$gallery['id']); ?>">Remover</a>
    </li>
<?php endforeach; ?>
<?php else: ?>
    <p>Não existe nenhuma galeria cadastrada no momento</p>
<?php endif; ?>
</ul>