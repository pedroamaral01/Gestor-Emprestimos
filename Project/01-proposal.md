# **CSI606-2024-02 - Proposta de Trabalho Final**

## *Discente: Pedro Henrique Amaral Estevão*

# 1. Tema

O trabalho final tem como tema o desenvolvimento de um **Sistema de Gerenciamento de Finanças para Prestadores de Empréstimos**. O objetivo é oferecer um controle detalhado sobre as dívidas e prestações dos clientes, permitindo que o prestador registre, consulte e administre informações sobre os empréstimos concedidos e os clientes associados a esses empréstimos. Além disso, o sistema contará com critérios de análise de crédito para a concessão de novos empréstimos, garantindo a integridade e segurança dos dados por meio de identificadores exclusivos e validação de informações.

---

# 2. Escopo

Este projeto terá as seguintes funcionalidades:

## Cadastro de Clientes:

- Inclusão de dados pessoais (nome completo, CPF, endereço, etc.).
- Possibilidade de cadastrar clientes mesmo que não possuam empréstimos ativos.

## Cadastro e Gestão de Empréstimos:

- Registro de empréstimos com informações como valor, prazo de pagamento, taxa de juros e classificação (pessoa física ou jurídica).
- Criação de um código de identificação exclusivo para cada empréstimo.
- Associação de um empréstimo a um cliente específico.
- Alteração e consulta de detalhes dos empréstimos.

    ## Análise de Crédito:

- Implementação de uma lógica para determinar a quantidade máxima de empréstimo que um cliente pode receber, com base no histórico de crédito, renda, entre outros fatores.
- Restrições para garantir que apenas empréstimos que atendam aos critérios de análise de crédito sejam concedidos.

## Relatórios e Consultas:

- Geração de relatórios sobre empréstimos ativos e quitados.
- Consulta de clientes com empréstimos pendentes e pagamentos em atraso.

## Funcionalidades Adicionais:

- Tela de login para acesso restrito aos administradores do sistema.
- Interface para visualizar todos os empréstimos cadastrados e os clientes associados.

---

# 3. Restrições

Neste trabalho, **não serão considerados**:

- Mecanismos automatizados de cobrança ou renegociação de dívidas.
- Integração com instituições financeiras externas para validação de crédito.
- Pagamentos online ou gestão de transações financeiras automatizadas.
- Suporte a múltiplos usuários com diferentes níveis de permissão além dos administradores.

---

# 4. Protótipo

![image](https://github.com/user-attachments/assets/195a34aa-9781-4921-a2ba-b2ee1cc3d1a6)

Protótipo feito utilizando o Whimsical: [Link do projeto](https://whimsical.com/gerenciamento-emprestimo-8fWPEYDCDrsiA9hdyPVvFK)

