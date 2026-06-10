# Uploads API

API desenvolvida em PHP com Laravel para colocar em pratica estudos sobre desenvolvimento backend, organizacao de codigo e conceitos de Clean Architecture.

O projeto nasce com a ideia de permitir que um usuario possa se cadastrar, criar e editar suas pastas e fazer upload de arquivos. A funcionalidade de upload ainda esta em desenvolvimento, assim como outras partes do sistema. Este repositorio tem fins de estudo e portfolio, portanto ainda nao representa uma aplicacao concluida ou pronta para producao.

## Objetivos do Projeto

- Praticar PHP e Laravel em um projeto com regras de negocio reais.
- Usar conceitos de Clean Architecture para guiar a separacao entre dominio, casos de uso, infraestrutura e detalhes do framework.
- Construir uma API para gerenciamento de usuarios, pastas e arquivos.
- Evoluir o projeto de forma incremental, documentando aprendizados e decisoes tecnicas.

## Tecnologias

- PHP 8.3+
- Laravel 13
- Composer
- Banco de dados configuravel pelo Laravel

## Funcionalidades Planejadas

- Cadastro e autenticacao de usuarios.
- Criacao, edicao e organizacao de pastas.
- Upload e gerenciamento de arquivos.
- Geracao de URLs pre-assinadas para envio ou acesso a arquivos.

## Status

Projeto em desenvolvimento. Algumas funcionalidades ainda podem estar incompletas, instaveis ou apenas parcialmente implementadas.

## Como Baixar e Rodar

### Pre-requisitos

Antes de comecar, tenha instalado:

- PHP 8.3 ou superior
- Composer
- Um banco de dados compativel com Laravel, como SQLite, MySQL ou PostgreSQL

### Instalacao

Clone o repositorio:

```bash
git clone https://github.com/PedroHSSodre/file-uploads-api
cd uploads-api
```

Instale as dependencias do PHP:

```bash
composer install
```

Crie o arquivo de ambiente:

```bash
cp .env.example .env
```

No Windows PowerShell, se preferir:

```powershell
Copy-Item .env.example .env
```

Gere a chave da aplicacao:

```bash
php artisan key:generate
```

Configure as variaveis de banco de dados no arquivo `.env` e execute as migrations:

```bash
php artisan migrate
```

### Rodando o Projeto

Para iniciar a API localmente:

```bash
php artisan serve
```

O projeto ficara disponivel, por padrao, em:

```text
http://127.0.0.1:8000
```

### Setup Automatico

O projeto tambem possui um script de setup:

```bash
composer run setup
```

Esse comando instala dependencias, cria o `.env` quando necessario, gera a chave da aplicacao e executa as migrations.
