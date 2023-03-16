### Arquivo feito para testes direto no container

Rodar as linhas no terminal:

sudo docker pull phpdockerio/php:8.1-fpm
sudo docker run --name testeasyncphp -it -v "$PWD:/public" phpdocker_php-fpm bash

vá até a pasta public do seu container

no bash execute:
composer install

para rodar:
php arquivo_desejado.php
ou podemos executar com timer:
time php arquivo_desejado.php