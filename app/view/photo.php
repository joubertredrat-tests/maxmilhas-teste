<!DOCTYPE html>
<html>
<head>
    <title>Fotos da galeria <?php echo $Gallery->name; ?></title>
    <link rel="stylesheet" href="<?php echo \App\Functions::getAppUrl('assets/css/index.css'); ?>" />
</head>
<body>
    <h2>Foto <?php echo $Photo->filename; ?></h2>
    <a href="<?php echo \App\Functions::getAppUrl(); ?>">Voltar</a>
    <div class="container">
        <div class="column-center">
            <img src="<?php echo \App\Functions::getAppUrl('store/'.$Gallery::FOLDER_PREFIX.$Gallery->id.'/').$Photo->filename; ?>" style="max-width: 80%;" />
        </div>
        <div class="column-left">
            <?php if(!$Photo->isFirst()): ?>
                <a href="<?php echo \App\Functions::getAppUrl('display/gallery/'.$Gallery->id.'/photo/'.$Photo->getPrevious()); ?>">
                    <img src="<?php echo \App\Functions::getAppUrl('assets/images/seta-esquerda.jpg'); ?>">
                </a>
            <?php else: ?>
                <img onclick="alert('Esta é a primeira imagem');" src="<?php echo \App\Functions::getAppUrl('assets/images/seta-esquerda.jpg'); ?>">
            <?php endif; ?> 
        </div>
        <div class="column-right">
            <?php if(!$Photo->isLast()): ?>
                <a href="<?php echo \App\Functions::getAppUrl('display/gallery/'.$Gallery->id.'/photo/'.$Photo->getNext()); ?>">
                    <img src="<?php echo \App\Functions::getAppUrl('assets/images/seta-direita.jpg'); ?>">
                </a>
            <?php else: ?>
                <img onclick="alert('Esta é a última imagem');" src="<?php echo \App\Functions::getAppUrl('assets/images/seta-direita.jpg'); ?>">
            <?php endif; ?> 
        </div>
    </div>
</body>
</html>