<a href="<?php echo \App\Functions::getAppUrl('admin/gallery'); ?>">Voltar</a>
<h2>Fotos da galeria <?php echo $Gallery->name; ?></h2>
<a href="<?php echo \App\Functions::getAppUrl('admin/photo-new/'.$Gallery->id); ?>">Novo</a>
<ul>
<?php foreach ($photos as $photo): ?>
    <li>
        <img src="<?php echo \App\Functions::getAppUrl('store/'.$Gallery::FOLDER_PREFIX.$Gallery->id.'/').$photo['filename']; ?>" style="max-width: 5%;" />
        <span title="<?php echo $photo['original_filename']; ?>"><?php echo $photo['filename']; ?></span> -
        <a href="<?php echo \App\Functions::getAppUrl('admin/photo/'.$gallery['id']); ?>">Subir</a> | 
        <a href="<?php echo \App\Functions::getAppUrl('admin/gallery-edit/'.$gallery['id']); ?>">Descer</a> | 
        <a href="<?php echo \App\Functions::getAppUrl('admin/photo-remove/'.$photo['id']); ?>">Remover</a>
    </li>
<?php endforeach; ?>
</ul>