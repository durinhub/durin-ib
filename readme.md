ImageBoard ![logo]
======

### Tecnologias utilizadas
+ [PHP 8](https://www.php.net/)
+ [Laravel 11](https://laravel.com/)
+ [Bootstrap](https://getbootstrap.com/)
+ [JQuery](https://jquery.com/)
+ [MySQL](https://www.mysql.com/)

Instalação
=====

Após instalar os componentes citados acima, criar um usuário SQL e um banco de dados para ele:

```SQL
create user NOME_USUARIO@localhost identified by 'SENHA_USUARIO';

create database NOME_BANCO_DE_DADOS;

grant all privileges on NOME_BANCO_DE_DADOS.* to NOME_USUARIO@localhost with grant option;
```

Criar um arquivo chamado .env e apontar as configurações do sistema:

```
APP_NAME=Exemplo
APP_ENV=local
APP_KEY=base64:CHAVE_DO_APP_BASE64
APP_DEBUG=true
APP_LOG_LEVEL=debug
APP_URL=http://exemplo.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=NOME_BANCO_DE_DADOS
DB_USERNAME=NOME_USUARIO
DB_PASSWORD=SENHA_USUARIO
```

Instalar dependências do composer:

```
composer install
```

Preparar o banco de dados:

```
php8 artisan migrate

php8 artisan db:seed
```

Configurar os marmelos

```
mv app/Marmelos/Marmelos.example.php app/Marmelos/Marmelos.php
```

Renomear a classe `MarmelosExemplo` para `Marmelos` e definir as palavras que quer que sejam filtradas conforme comentário no arquivo PHP

Inserir imagens para o ícone e logo em:

```
/storage/res/logo-ib.png
/storage/res/icon-ib.png
```

Inserir imagem da carteira de monero para doação em:

```
public/storage/res/doacao-monero.png
```

Iniciar a aplicação usando o Artisan:


```
php8 artisan serve
```

[logo]: /public/storage/res/logo-ib.png "Icon"