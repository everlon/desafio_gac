# Sistema de Carteira com Laravel

Este é um sistema simples de gerenciamento de carteiras para um desafio em Laravel, onde é possível realizar depósitos, transferências entre usuários e visualizar transações. O sistema está preparado para rodar em um ambiente Docker e possui testes automatizados.

## Requisitos

- Docker
- Docker Compose

## Configuração e Subida dos Containers

### Passos para iniciar o projeto:

1. **Clone o repositório**:
   ```bash
   git clone https://github.com/seu-repositorio.git   
   cd seu-repositorio
   ```
2. **Subir os containers Docker**:
   No diretório raiz do projeto, execute:
   ```bash
   docker-compose up --build
   ```
   Isso criará e iniciará os containers definidos no `docker-compose.yml`:
   - O container do **Laravel** estará disponível na porta `8000`.
   - O **MySQL** estará rodando na porta `3306`.

3. **Copiar o projeto para o container**:
   O projeto irá ser copiado para o container confirme o arquivo docker-composer.yml

4. **Configurar o arquivo `.env`**:
   - Copie o arquivo `.env.example` e renomeie para `.env`.
   - No arquivo `.env`, ajuste as variáveis de ambiente para conectar ao banco de dados:
     ```env
     DB_CONNECTION=mysql
     DB_HOST=db
     DB_PORT=3306
     DB_DATABASE=laravel_wallet
     DB_USERNAME=user
     DB_PASSWORD=password
     ```

5 **Rodar as migrações do banco de dados**:
   Execute as migrações para criar as tabelas no banco de dados:
   ```bash
   php artisan migrate:flush
   ```

## Uso do Sistema

- Acesse o sistema no navegador em: `http://localhost:8000`.
- Após o login, você pode visualizar o saldo da sua carteira, realizar depósitos e transferências.
- As transações podem ser visualizadas em uma página separada, listando os envios e recebimentos.
- Se necessário acesse `http://localhost:8000/register` para criar seu usuário.
- Irá ser criado o número de sua conta automaticamente que será necessária para receber os valores no sistema.

### Funcionalidades Principais:
- **Depósito**: Permite adicionar fundos à carteira.
- **Transferência**: Permite transferir saldo para outro usuário.
- **Visualizar transações / Extrato**: Acompanhe o histórico de transações (enviadas e recebidas).

## Executando Testes

1. **Configurar o banco de dados de testes**:
   No arquivo `.env.testing`, configure o banco de dados de testes:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=db
   DB_PORT=3306
   DB_DATABASE=laravel_wallet_test
   DB_USERNAME=user
   DB_PASSWORD=password
   ```

2. **Rodar as migrações de teste**:
   Execute as migrações para o ambiente de teste:
   ```bash
   php artisan migrate --env=testing
   ```

3. **Executar os testes**:
   Para rodar os testes automatizados, execute:
   ```bash
   php artisan test
   ```

   Os testes incluem verificações das funcionalidades de depósito, transferência e visualização de transações, garantindo que o sistema esteja funcionando corretamente.
