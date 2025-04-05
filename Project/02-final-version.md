# Gestor de Empréstimos

## Funcionalidades Principais

- **Cadastro de cliente**: Sistema completo para registro de informações dos clientes
- **Análise de Crédito**: Função especializada para avaliação de risco creditício
- **Cadastro de empréstimo**: Gestão completa das operações de crédito
- **Pagamento de empréstimo**: Processamento e registro de pagamentos
- **Tela de login**: Acesso restrito para administradores
- **Visualização de empréstimos**: Interface para consulta de todos os empréstimos e clientes associados

##  Não Implementadas

- Opção para gerar relatorio como de pds ou planilha do excel

## Desafios do Projeto

- Desenvolvimento do protótipo de interface
- Definição da estrutura do banco de dados
- Implementação do sistema de agendamento de tarefas

## Outras funcionalidades implementadas
- **Atualização de status**: Verificação e atualização automática do status do empréstimo baseado no vencimento das parcelas utlizando agendamento de tarefas
- **Opção de pagar multa por atraso de pagamento**

##  Principais desafios e dificuldades
- Desenvolvimento do protótipo de interface
- Definição da estrutura do banco de dados

5. - ## Instalação e Configuração
- **Docker** e **Docker Compose** instalados.
- **Node**

### 1. Clone o repositório do projeto:

   ```bash
   git clone https://github.com/pedroamaral01/Gestor-Emprestimos
   ```
   ```bash
   cd nomeDaPasta
   ```
   
### 2. Renomeie o arquivo .env.example para .env:

### 3. Execute o Composer e suba o ambiente Docker usando o Laravel Sail:

```bash
   composer install
   ```
  ```bash
   ./vendor/bin/sail up
   ```

### 4. Execute as migrações para criar as tabelas no banco de dados:

   ```bash
   ./vendor/bin/sail artisan migrate
   ```

### 5. Configuração do Banco de Dados: Para acessar o banco de dados, configure um cliente como o DBeaver, utilizando as credenciais definidas no arquivo `.env`.

   Importante: caso utilize o DBeaver, edite as configurações no driver MySQL ao conectar:
   - `"useSSL"` ⇒ `false`
   - `"allowPublicKeyRetrieval"` ⇒ `true`

### Configuração do Frontend

1. Instale as dependências do Node.js:
```bash
npm install
 ```
 ## Execução do Projeto

### 1. Inicie os containers Docker (backend)
```bash
./vendor/bin/sail up -d
   ```
### 2. Execute o ambiente de desenvolvimento
   ```bash
   npm run dev
   ```
