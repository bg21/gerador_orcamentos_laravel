---
name: laravel-doc-generator
description: >
  Gera documentação completa de classes PHP/Laravel em três formatos: PHPDoc inline nos arquivos,
  README.md por classe/módulo e Markdown formatado para wiki ou Notion. Analisa qualquer tipo de
  classe Laravel — Models, Controllers, Services, Form Requests, Jobs, Events, Listeners, Policies,
  Observers, Commands e Traits. Use esta skill SEMPRE que o usuário quiser documentar classes PHP,
  gerar PHPDoc automaticamente, criar README de módulos Laravel, produzir documentação para wiki ou
  Notion, ou disser coisas como "documenta minhas classes", "gera a doc do meu sistema", "quero um
  README para meus services", "adiciona PHPDoc no meu código", "cria documentação técnica do meu
  projeto Laravel". Acione mesmo quando o usuário só mencionar "documentação" em contexto Laravel.
---

# Laravel Doc Generator

Gera documentação completa de classes PHP/Laravel em três formatos distintos, analisando profundamente
o código para produzir conteúdo útil, preciso e não genérico.

---

## Integração com `laravel-ide-helper`

O pacote [`barryvdh/laravel-ide-helper`](https://github.com/barryvdh/laravel-ide-helper) e esta skill são **complementares** — use os dois juntos para documentação completa:

| Responsabilidade | `laravel-ide-helper` | Esta skill |
|------------------|---------------------|------------|
| PHPDoc de colunas/casts/relations inferidos do banco | ✅ Automático | — |
| PHPDoc de Facades | ✅ Automático | — |
| Descrição de propósito e comportamento | — | ✅ |
| README e Wiki por módulo | — | ✅ |
| Side-effects, avisos, exemplos de uso | — | ✅ |

### Pré-passo recomendado (quando o usuário tem acesso ao projeto)

Se o usuário **tem acesso ao terminal do projeto**, sugira rodar o ide-helper antes de gerar a documentação com esta skill. Isso enriquece os Models com tipos precisos de colunas antes de você adicionar as descrições de negócio:

```bash
# Instalar (apenas dev)
composer require --dev barryvdh/laravel-ide-helper

# Gerar PHPDoc de Models diretamente nos arquivos (recomendado para Models)
php artisan ide-helper:models -RW

# Gerar autocompletar de Facades
php artisan ide-helper:generate

# Gerar metadados para PHPStorm
php artisan ide-helper:meta
```

Após rodar `ide-helper:models -RW`, os Models já terão `@property` para cada coluna do banco. Esta skill então **complementa** adicionando: descrição da classe, propósito dos campos, relacionamentos comentados, scopes explicados, README e Wiki.

Se o usuário **não tem acesso ao terminal** (só colou o código), siga normalmente — esta skill funciona de forma independente.

---

## Fluxo de Trabalho

### 1. Receber o Código

Antes de pedir o código, **verifique se o usuário tem acesso ao terminal do projeto**. Se sim, sugira rodar `php artisan ide-helper:models -RW` nos Models primeiro (veja seção acima). Depois peça o código.

O usuário pode fornecer o código de três formas:
- **Colado na conversa**: ler diretamente do contexto
- **Upload de arquivo(s) .php**: ler via `view` ou bash
- **Múltiplos arquivos**: processar um a um e consolidar se necessário

Se não houver código ainda, peça ao usuário que cole ou faça upload.

### 2. Identificar o Tipo de Classe

Detecte automaticamente o tipo com base em:
- Namespace: `App\Models`, `App\Http\Controllers`, `App\Services`, `App\Http\Requests`, etc.
- Herança: `extends Model`, `extends Controller`, `extends FormRequest`, `extends Job`, etc.
- Traits usadas, métodos presentes (ex: `handle()`, `rules()`, `up()`/`down()`)

Tipos suportados: **Model · Controller · Service · FormRequest · Job · Event · Listener · Policy · Observer · Command · Trait · Migration · Seeder**

### 3. Confirmar Formato de Saída

Se o usuário não especificou, pergunte qual(is) formato(s) deseja:
- `phpdoc` → bloco `/** */` para inserir diretamente no arquivo PHP
- `readme` → arquivo README.md para a pasta do módulo/classe
- `wiki` → Markdown rico para colar em Notion, Confluence, GitHub Wiki

Pode gerar múltiplos formatos ao mesmo tempo.

### 4. Analisar Profundamente o Código

Para cada classe, extraia:

| O que analisar | Como documentar |
|----------------|-----------------|
| Propósito geral da classe | Descrição no topo, 1–3 frases objetivas |
| Dependências injetadas | Liste com tipo e papel no construtor |
| Métodos públicos | Assinatura, parâmetros, retorno, descrição do comportamento |
| Métodos protegidos/privados relevantes | Documente se forem complexos |
| Relationships (Eloquent) | Liste com tipo (`hasMany`, `belongsTo`, etc.) e modelo relacionado |
| Scopes | Descreva o filtro que aplicam |
| Rules (FormRequest) | Explique cada regra em linguagem natural |
| Events disparados | Note side-effects importantes |
| Exceptions lançadas | Liste `@throws` quando relevante |
| Retornos | Seja específico: `Collection<User>`, `JsonResponse`, `void`, etc. |

### 5. Gerar a Documentação

Consulte `references/formats.md` para os templates exatos de cada formato.

Regras gerais de qualidade:
- **Nunca seja genérico.** "Retorna dados do usuário" é inútil. "Retorna a coleção de pedidos ativos do usuário, ordenados por `created_at` DESC" é útil.
- **Descreva comportamento, não código.** O leitor já tem o código; explique *o que* e *por que*, não *como*.
- **Preserve o estilo do projeto.** Se o projeto usa PT-BR, documente em PT-BR. Se usa EN, use EN.
- **Não invente.** Se não conseguir inferir o propósito de algo, use `// TODO: descrever propósito` em vez de inventar.
- **Para PHPDoc**: use tipos PHP modernos (`int`, `string`, `bool`, `array`, `Collection`, classes FQCN quando relevante).

### 6. Entregar o Resultado

- **PHPDoc**: entregue o arquivo PHP completo com os blocos inseridos, pronto para copiar/substituir
- **README**: crie um arquivo `.md` e use `present_files` para o usuário baixar
- **Wiki/Notion**: entregue o Markdown na conversa (pode também criar arquivo)

Se foram múltiplas classes, consolide num único arquivo Markdown ou gere um README por módulo conforme pedido.

---

## Dicas por Tipo de Classe

### Model
- Liste todas as `$fillable`/`$guarded` e explique o propósito de cada campo se possível
- Documente relationships com o modelo alvo: `@return HasMany<Order>`
- Liste scopes e o filtro que aplicam
- Documente eventos do modelo (`creating`, `updated`, etc.) se houver `$dispatchesEvents` ou observers

### Controller
- Documente cada action (método HTTP) com: rota esperada, parâmetros, o que faz, o que retorna
- Infira a rota a partir do nome do método (`index`, `show`, `store`, `update`, `destroy`)
- Liste middlewares aplicados se visíveis no construtor

### Service
- Descreva o domínio de responsabilidade da classe inteira no topo
- Para cada método público, documente entradas, saídas e side-effects (emails, eventos, filas)
- Aponte dependências externas (APIs, outros services)

### FormRequest
- Para cada regra em `rules()`, explique em linguagem natural o que valida
- Documente `authorize()`: quem pode fazer esta request?
- Liste mensagens customizadas de `messages()` se houver

### Job / Event / Listener
- Documente o gatilho: o que dispara este Job/Event?
- Documente o efeito: o que acontece quando é processado?
- Para Jobs: mencione queue, tries, timeout se definidos

---

## Referências

- Templates exatos de cada formato → `references/formats.md`
- Exemplos de saída por tipo de classe → `references/examples.md`
