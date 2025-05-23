# Sistema de Cadastro de Produtos
## Descrição do Teste

Este projeto é um sistema de cadastro de produtos com as seguintes características:

- Login para um usuário padrão.
- Listagem de produtos após o login.
- Opções para Editar, Cadastrar e Inativar produtos.
- Produto com os campos:
  - Título
  - Imagens (múltiplas)
  - Preço de venda
  - Custo
  - Descrição com HTML limitado às tags `<p>`, `<br>`, `<b>` e `<strong>`.

### Validações

- Preço não pode ser inferior a custo + 10%.
- Somente imagens JPG e PNG são aceitas.
- Apenas as tags HTML permitidas podem ser usadas na descrição.

---

## Arquitetura

- PHP 8  
- MySQL 8  
- Laravel 9  
- Vue.js  
- Docker

---

## Instalação e Execução

### Pré-requisitos

- Docker instalado (para rodar containers do banco de dados e da aplicação)
- Composer instalado
- Node.js e npm instalados
- Git instalado

### Passos para rodar o projeto

1. Clone o repositório:
   ```bash
   git clone https://github.com/seu-usuario/seu-projeto.git
   cd seu-projeto

2. No seu terminal:
   ```bash
   composer install
   npm install

3. Gere uma nova chave pro env:
   ```bash
   php artisan key:generate

4. buildar os container Docker - (mysql, amor_app):
   ```bash
    docker-compose up --build   

5. No exec do Container amor_app suba as migrate e seeder:
   ```bash
    php artisan migrate --seed

Um usuario e senha serão gerados:
    email: admin@admin.com & senha: admin123

6. Inicie o servidor Laravel:
   ```bash
    php artisan serve

Por padrão, o Laravel rodará em:
http://127.0.0.1:8000 ou http://localhost:8000

7. Rode o build do frontend em modo desenvolvimento:
   ```bash
    pm run dev

## Testes

Acesse a aplicação no navegador em http://127.0.0.1:8000 ou http://localhost:8000.

Faça login com o usuário padrão.

Teste as funcionalidades de listagem, criação, edição e inativação de produtos.

## Contato
Em caso de dúvidas ou sugestões: Pisonj30@gmail.com 