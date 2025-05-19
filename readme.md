# Sistema de Cadastro de Produtos:

## Descrição:

- Um sistema para cadastrar e gerenciar produtos de uma loja online.
- Os usuários podem pesquisar produtos por nome, categoria ou preço.
- Uma área de administração para adicionar, editar e excluir produtos.

## Tecnologias:

- HTML: Estrutura das páginas de produtos e administração.
- CSS: Estilização da loja online.
- JavaScript: Interatividade, como filtros de pesquisa e validação de formulários.
- PHP: Lógica do lado do servidor para gerenciar produtos e usuários, além da conexão com o banco de dados.
- Banco de Dados (MySQL): Armazenamento de informações dos produtos.

### Cores:
- #041122
- #259073
- #7fda89
- #c8e98e
-#fdfff5

-rgb(4, 17, 34)
-rgb(37, 144, 115)
-rgb(127, 218, 137)
-rgb(200, 233, 142)
-rgb(253, 255, 245)


## Funcionalidades:

- Página inicial com lista de produtos.
- Páginas de detalhes do produto.
- Formulário de pesquisa de produtos.
- Sistema de login/registro para administradores.
- Painel de administração para gerenciamento de produtos.

## Banco de dados

- Categories : Salvar categorias dos produtos;
- Subcategories : Salva as subcategorias tidas em produtos, referente a categories;
- Products: Registra os produtos da lója;
- Products Images: Para salvar as imagens de produtos;
- Users: Salva os usuários do sistema;

-- Para Adicionar --
- Carrinho : Produtos salvos para compra futura;
- Transação : Registro de transações de vendas dos produtos;

Organizar a lógica de compras para um site de **e-commerce** envolve estruturar tanto o **banco de dados (tabelas)** quanto a **lógica de negócios** (backend), de modo a garantir que as funcionalidades funcionem de forma fluida e segura. Abaixo está uma explicação completa com foco em **tabelas essenciais** e **fluxo de compra**:

---

## 🔧 1. **Tabelas principais no banco de dados**

Essas são as principais entidades com seus campos mais importantes:

### `users` (clientes)
- `id`
- `nome`
- `email`
- `senha_hash`
- `endereço`
- `telefone`
- `data_criacao`

### `products`
- `id`
- `nome`
- `descricao`
- `preco`
- `estoque`
- `categoria_id` (FK)
- `imagem_url`

### `categories`
- `id`
- `nome`

### `cart` (carrinho de compras)
- `id`
- `user_id` (FK)
- `status` (ativo, finalizado, abandonado)
- `data_criacao`

### `cart_items`
- `id`
- `cart_id` (FK)
- `product_id` (FK)
- `quantidade`
- `preco_unitario`

### `orders` (pedidos)
- `id`
- `user_id` (FK)
- `total`
- `status` (pendente, pago, enviado, cancelado, etc.)
- `data_pedido`
- `endereco_entrega`

### `order_items`
- `id`
- `order_id` (FK)
- `product_id` (FK)
- `quantidade`
- `preco_unitario`

### `payments`
- `id`
- `order_id` (FK)
- `metodo_pagamento`
- `valor`
- `status` (autorizado, recusado, etc.)
- `data_pagamento`

---

## 🔁 2. **Fluxo da lógica de compra**

### Etapas básicas:

1. **Usuário navega pelo site** e visualiza produtos.
2. **Adiciona produtos ao carrinho** (cria/atualiza `cart` e `cart_items`).
3. **Vai para o checkout** (revisão do carrinho, endereço de entrega, forma de pagamento).
4. **Confirma o pedido** (cria `orders` e `order_items`, move os dados do carrinho para o pedido).
5. **Processamento do pagamento** (registra na tabela `payments`, com integração a gateway).
6. **Atualiza estoque** de cada produto comprado.
7. **Admin recebe pedido** (para separação, envio, etc.).

---

## ✅ 3. Regras de negócio importantes

- Não permitir compra com produtos fora de estoque.
- Validação de endereço antes do pedido.
- Transação atômica: a criação do pedido e o débito no estoque devem ocorrer em uma transação única para evitar inconsistências.
- Lógica de carrinho abandonado (auto expiração ou recuperação por e-mail).
- Segurança com autenticação JWT, criptografia de senhas, e proteção contra SQL injection.

---

## 🧠 Extras úteis

- Logs de atividade do usuário (analytics).
- Tabela de cupons/descontos.
- Suporte a múltiplos métodos de pagamento.
- API RESTful ou GraphQL para integração com frontend (React, Vue, etc.).

---

Se quiser, posso montar um diagrama ER (entidade-relacionamento) básico para visualizar essas tabelas. Gostaria disso?