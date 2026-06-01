---
name: laravel-best-practices
description: >
  Aplica as melhores práticas da comunidade Laravel ao gerar, revisar ou corrigir código PHP/Laravel.
  Use esta skill SEMPRE que o usuário estiver escrevendo ou pedindo código Laravel — controllers, models,
  migrations, routes, services, form requests, Blade templates, Eloquent queries, testes, ou qualquer
  estrutura de projeto Laravel. Acione também quando o usuário perguntar "como fazer X no Laravel",
  "qual a forma correta de...", "como estruturar...", ou pedir revisão de código Laravel existente.
  Aplique mesmo que o usuário não mencione explicitamente "boas práticas".
---

# Laravel Best Practices

Guia de referência baseado em https://github.com/alexeymezenin/laravel-best-practices (12k+ estrelas).

Sempre que gerar ou revisar código Laravel, aplique as regras abaixo. Para exemplos detalhados com código, consulte `references/practices.md`.

---

## Regras Fundamentais

### Arquitetura

- **Single Responsibility**: cada classe tem uma responsabilidade. Controllers orquestram; models contêm lógica de DB; services contêm regras de negócio.
- **Fat Models, Skinny Controllers**: lógica de consulta fica no Model, não no Controller.
- **Service Classes**: lógica de negócio complexa vai em `app/Services/`, não em controllers.
- **Form Requests**: validação sempre em classes `app/Http/Requests/`, nunca inline no controller.
- **Dependency Injection**: injete dependências via construtor. Evite `new ClassName()` dentro de métodos.

### Eloquent e Banco de Dados

- Prefira Eloquent a Query Builder raw ou SQL puro.
- Use **eager loading** (`with()`) para evitar o problema N+1 — nunca execute queries dentro de loops Blade.
- Use **scopes** para reutilizar condições de query em vez de duplicar `where()`.
- Use **`chunk()`** para iterar sobre grandes volumes de dados.
- Use **mass assignment** com `create($request->validated())` em vez de atribuir campo a campo.
- Defina `$casts` para datas — manipule Carbon no PHP, não formate no Blade.

### Configuração e Constantes

- Nunca chame `env()` diretamente no código — passe pelo `config()`.
- Use arquivos de linguagem (`__('chave')`) em vez de strings hardcoded.
- Use constantes de Model (`Article::TYPE_NORMAL`) em vez de strings mágicas.

### Qualidade de Código

- Nomes descritivos de métodos e variáveis em vez de comentários.
- Evite DocBlocks — use type hints e return types do PHP moderno.
- Métodos pequenos que fazem uma única coisa.
- Prefira sintaxe curta do Laravel: `now()`, `back()`, `session()`, `->latest()`, `->oldest()`, `compact()`, null-safe operator (`?->`).

### Separação de Camadas

- Zero JS/CSS em templates Blade.
- Zero HTML em classes PHP.
- Lógica zero em arquivos de rotas.
- Minimize PHP puro (vanilla) em Blade.

---

## Convenções de Nomenclatura

| O quê | Convenção | Exemplo correto |
|-------|-----------|-----------------|
| Controller | Singular | `ArticleController` |
| Model | Singular | `User` |
| Tabela | Plural snake_case | `article_comments` |
| Pivot | Singulares alfabéticos | `article_user` |
| Coluna | snake_case | `meta_title` |
| FK | `{model}_id` | `article_id` |
| Método | camelCase | `getActiveUsers` |
| Variável | camelCase | `$activeUsers` |
| View | kebab-case | `show-filtered.blade.php` |
| Config | snake_case | `google_calendar.php` |
| Route URL | Plural | `articles/1` |
| Route name | snake_case + ponto | `users.show_active` |
| Interface | Adjetivo/substantivo | `AuthenticationInterface` |
| Trait | Adjetivo | `Notifiable` |
| Enum | Singular | `UserType` |
| FormRequest | Singular | `UpdateUserRequest` |
| Seeder | Singular | `UserSeeder` |

---

## Ferramentas Padrão Laravel (prefira sempre)

| Tarefa | Use |
|--------|-----|
| Autorização | Policies |
| Autenticação | Built-in Auth |
| API Auth | Sanctum / Passport |
| Compilação de assets | Vite |
| ORM | Eloquent |
| Templates | Blade |
| Testes unitários | Pest / PHPUnit |
| Agendamento | Laravel Scheduler |
| Filas | Laravel Queues |
| Validação | Form Request classes |

---

## Checklist de Revisão de Código

Ao revisar código Laravel, verifique:

- [ ] Controller tem mais de ~20 linhas de lógica? → extrair para Service
- [ ] Validação inline no controller? → mover para Form Request
- [ ] `new ClassName()` dentro de método? → usar injeção de dependência
- [ ] Query dentro de loop Blade? → eager loading com `with()`
- [ ] `env('KEY')` diretamente no código? → usar `config('arquivo.key')`
- [ ] String hardcoded de mensagem? → usar `__('chave')`
- [ ] Método com mais de ~20 linhas? → quebrar em métodos menores
- [ ] DocBlock onde type hint resolveria? → remover DocBlock
- [ ] Condição complexa inline? → extrair para método `is*()` / `has*()`
- [ ] Atribuição campo a campo antes de `save()`? → usar `create()` com `validated()`

---

## Referências

Para exemplos completos de código (antes/depois) de cada prática, consulte:
→ `references/practices.md`
