App de galerias de imagens
=======

Este aplicativo permite gerenciar e exibir várias galerias e suas respectivas fotos

##Requisitos##
* PHP 5.4 ou superior
* Servidor web Apache
* Mysql 5.5, MariaDB 10.X ou superior

##Instalação##

Para realizar a instalação do aplicativo, basta fazer o download do código fonte neste repositório, executar pelo webserver do PHP `php -S 0.0.0.0:9000 public/index.php` e acessar o endereço public conforme exemplo `http://dev.local/install`.

No navegador, informar o nome do banco de dados, usuário e senha com a permissão, clicar em enviar e pronto, seu aplicativo está instalado.

##Gestão##

A gestão é feita na interface de administração, conforme exemplo `http://dev.local/admin`. O usuário padrão criado na instalação é `admin` e senha `admin`.

##Recursos##

O aplicativo permite gestão de várias galerias, várias imagens das galerias e também ordenar as fotos conforme desejar.

##Limitações##

* Como não era o propósito inicial do aplicativo, não existe validações em relação ao tamanho das imagens.
* Devido ao prazo causado pela falha de interpretação da tarefa, algumas validações de formulário não foram feitas.