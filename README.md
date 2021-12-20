# MeAdota2

## Descrição

Sistema de adoção de pets (animais de estimação)

## Clonando o Projeto

### Ferramentas Necessárias

- PHP 7 ou superior
- Composer
- MySQL/MariaDB

Abra o cmd/terminal e execute o comando abaixo:

    git clone https://github.com/yuri97real/MeAdota2.git

Entre na pasta do projeto e baixe as dependências com o composer:

    cd MeAdota2/
    composer update

### Migrations

Você pode executar o arquivo "migrate.php" para gerar ou destruir as tabelas.

Use as flags abaixo, na execução do arquivo:

- up: cria as tabelas
- down: destroi as tabelas

### Exemplo

    php migrate.php --method=up
