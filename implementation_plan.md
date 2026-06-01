# Plano de Implementação: Módulo de Orçamentos (Quotes) no Dashboard

O próximo grande passo do sistema é o desenvolvimento do **Módulo de Orçamentos (Quotes)**. Esse módulo unirá os clientes e serviços cadastrados para gerar propostas comerciais completas. Garantiremos o isolamento completo por tenant (`user_id`).

## User Review Required

> [!IMPORTANT]
> - O número do orçamento será gerado automaticamente (ex: `ORC-2026-0001`) baseado no ano atual e sequencial por usuário.
> - A criação e edição de orçamentos precisarão de uma interface dinâmica (Blade + Alpine.js ou Vanilla JS) para permitir adicionar múltiplos itens (serviços) dinamicamente no formulário.

## Proposed Changes

### 1. Banco de Dados e Lógica do Backend

#### [NEW] [QuoteController.php](file:///d:/xampp/htdocs/geradororcamentoslaravel/geradororcamentos/app/Http/Controllers/QuoteController.php)
* Criar controlador com as seguintes ações:
  * `index`: Listar orçamentos do usuário autenticado com paginação, filtros de status (Pendente, Aprovado, Vencido) e estatísticas rápidas.
  * `create`: Exibir o formulário enviando os clientes e serviços do usuário autenticado para seleção.
  * `store`: Validar e salvar o orçamento e seus itens (na tabela `quote_items`), calculando automaticamente o valor total (`total_amount`) aplicando o desconto (`discount`) especificado.
  * `show`: Exibir detalhes do orçamento (pré-visualização da proposta comercial).
  * `edit`: Exibir formulário de edição com os dados atuais e lista de itens carregados.
  * `update`: Atualizar dados do orçamento e sincronizar (adicionar/remover/atualizar) os itens.
  * `destroy`: Excluir orçamento e seus itens associados.

#### [MODIFY] [web.php](file:///d:/xampp/htdocs/geradororcamentoslaravel/geradororcamentos/routes/web.php)
* Registrar a rota resource para `quotes`:
  ```php
  Route::resource('quotes', QuoteController::class);
  ```

### 2. Interface do Usuário (UI/UX)

#### [NEW] Views de Orçamentos
* `resources/views/quotes/index.blade.php`: Listagem de orçamentos com badges de status, estatísticas e botão "Criar Orçamento".
* `resources/views/quotes/create.blade.php`: Formulário dinâmico com:
  * Seleção de Cliente.
  * Seleção/Inclusão de Itens (com descrição, quantidade, preço unitário e preço total calculado em tempo real).
  * Inputs para data de emissão, data de vencimento, notas e desconto (BRL ou percentual).
* `resources/views/quotes/edit.blade.php`: Formulário de edição semelhante à criação, pré-carregando os dados e itens existentes.
* `resources/views/quotes/show.blade.php`: Visualização da proposta comercial (design limpo imitando papel timbrado para conferência antes de exportar).

#### [MODIFY] [navigation.blade.php](file:///d:/xampp/htdocs/geradororcamentoslaravel/geradororcamentos/resources/views/layouts/navigation.blade.php)
* Adicionar o link "Orçamentos" no menu de navegação do painel.

### 3. Testes Automatizados

#### [NEW] [QuoteCrudTest.php](file:///d:/xampp/htdocs/geradororcamentoslaravel/geradororcamentos/tests/Feature/QuoteCrudTest.php)
* Criar testes cobrindo:
  * Usuários não autenticados não podem ver ou criar orçamentos.
  * CRUD completo de orçamentos e itens por um usuário autenticado.
  * Isolamento multi-tenant (Usuário A não pode ver/editar/deletar orçamentos do Usuário B).
  * Geração sequencial correta do `quote_number`.

---

## Verification Plan

### Automated Tests
- Executar a nova suite de testes criada:
  `php artisan test --filter=QuoteCrudTest`

### Manual Verification
- Acessar a dashboard local e testar o fluxo de ponta a ponta:
  1. Criar novo orçamento associando a um cliente.
  2. Adicionar 2 itens de serviço e aplicar desconto.
  3. Salvar e verificar os cálculos matemáticos na listagem e na visualização.
  4. Excluir o orçamento e validar o sumário de estatísticas.
