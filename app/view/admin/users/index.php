<a href="<?php echo \App\Functions::getAppUrl('admin'); ?>">Voltar</a>
<h2>Usuários</h2>
<a href="<?php echo \App\Functions::getAppUrl('admin/user-new'); ?>">Novo</a>
<ul>
<?php foreach ($users as $user): ?>
    <li>
        <?php echo $user['username']; ?> -
        <a href="<?php echo \App\Functions::getAppUrl('admin/user-edit/'.$user['id']); ?>">Editar</a>
    </li>
<?php endforeach; ?>
</ul>