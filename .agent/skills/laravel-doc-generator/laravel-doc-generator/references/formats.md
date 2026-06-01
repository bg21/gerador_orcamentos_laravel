# Formato de Saída — Templates

## Formato 1: PHPDoc

Insira blocos `/** */` imediatamente antes de cada classe e método público.

### Template — Cabeçalho de Classe

```php
/**
 * {Descrição objetiva do propósito da classe em 1–2 frases}.
 *
 * {Parágrafo opcional com contexto adicional, regras de negócio relevantes,
 * ou dependências externas importantes.}
 *
 * @package {Namespace completo}
 */
```

### Template — Método

```php
/**
 * {Descrição do que o método faz, em linguagem de negócio}.
 *
 * {Detalhes adicionais se necessário: side-effects, condições, comportamento em edge cases.}
 *
 * @param  {Tipo}  ${nome}  {Descrição do parâmetro}
 * @param  {Tipo}  ${nome}  {Descrição do parâmetro}
 * @return {Tipo retornado com detalhes}  {Descrição do retorno}
 * @throws {ExceptionClass}  {Quando esta exception é lançada}
 */
```

### Template — Property

```php
/** {Descrição do campo/propriedade} */
```

### Regras PHPDoc
- Sempre use FQCN para tipos de classes externas: `\Illuminate\Support\Collection` ou importe e use o nome curto
- Para coleções Eloquent use: `Collection<int, ModelName>`
- Para arrays tipados use: `array<string, mixed>` ou `list<int>`
- Omita `@param` e `@return` quando o tipo PHP nativo já está declarado na assinatura E a descrição não agrega valor
- Use `@deprecated` quando identificar método obsoleto
- Use `@see OtherClass::method()` para remeter a implementações relacionadas

---

## Formato 2: README.md por Classe/Módulo

Use este template para gerar um `README.md` na pasta do módulo (ex: `app/Services/README.md`).

```markdown
# {Nome da Classe ou Módulo}

> {Descrição em 1 frase — o que este módulo/classe faz}

---

## Visão Geral

{2–4 frases descrevendo o domínio de responsabilidade, quando usar esta classe,
e o que ela NÃO faz (limites de responsabilidade).}

---

## Dependências

| Dependência | Tipo | Papel |
|-------------|------|-------|
| `{ClassName}` | Serviço/Repository/etc. | {Para que é usado} |

---

## Métodos Públicos

### `{nomeDoMetodo}({params}): {retorno}`

{Descrição do comportamento. O que recebe, o que faz, o que retorna.}

**Parâmetros:**
- `${nome}` — {tipo} — {descrição}

**Retorno:** `{tipo}` — {descrição do retorno}

**Side-effects:** {eventos disparados, emails enviados, filas enfileiradas — ou "Nenhum"}

---

## Exemplos de Uso

```php
// {Comentário explicando o contexto do exemplo}
$resultado = app({ClassName}::class)->{metodo}({exemplo de argumento});
```

---

## Notas e Avisos

{Comportamentos não-óbvios, casos de borda, limitações conhecidas.}
```

---

## Formato 3: Markdown para Wiki/Notion

Use para documentação navegável em wikis, Confluence ou Notion. Mais rico que o README.

```markdown
# 📄 {Nome da Classe}

**Tipo:** {Model / Controller / Service / FormRequest / Job / ...}  
**Namespace:** `{App\Caminho\Completo}`  
**Arquivo:** `app/{caminho/relativo/Classe.php}`  

---

## 🎯 Propósito

{Descrição completa: o que faz, por que existe, qual problema resolve.
Pode ter 1–3 parágrafos. Inclua contexto de negócio quando relevante.}

---

## 🏗️ Dependências Injetadas

| Parâmetro | Tipo | Para que serve |
|-----------|------|----------------|
| `${variavel}` | `{Tipo}` | {Finalidade} |

---

## 📋 Métodos

### `{nomeDoMetodo}()`

| Campo | Detalhe |
|-------|---------|
| **Visibilidade** | `public` / `protected` / `private` |
| **Parâmetros** | `${nome}: {tipo}` — {descrição} |
| **Retorno** | `{tipo}` — {descrição} |
| **Lança** | `{ExceptionClass}` quando {condição} |
| **Side-effects** | {eventos, emails, filas — ou nenhum} |

**Descrição:**  
{Explicação detalhada do comportamento.}

---

## 🔗 Relacionamentos  *(apenas para Models)*

| Método | Tipo | Modelo Relacionado | Descrição |
|--------|------|--------------------|-----------|
| `orders()` | `hasMany` | `Order` | Pedidos feitos pelo usuário |

---

## ✅ Regras de Validação  *(apenas para FormRequests)*

| Campo | Regras | Descrição |
|-------|--------|-----------|
| `{campo}` | `required\|string\|max:255` | {O que valida em linguagem natural} |

---

## ⚙️ Configurações  *(para Jobs/Commands)*

| Propriedade | Valor | Descrição |
|-------------|-------|-----------|
| `$queue` | `{nome}` | Fila onde o job é processado |
| `$tries` | `{n}` | Tentativas antes de falhar |
| `$timeout` | `{n}s` | Tempo máximo de execução |

---

## 💡 Exemplos

```php
// {Contexto do exemplo}
{código de exemplo realista}
```

---

## ⚠️ Avisos e Limitações

- {Item 1}
- {Item 2}

---

*Documentação gerada automaticamente — revise e complemente conforme necessário.*
```

---

## Consolidando Múltiplas Classes (Wiki)

Quando o usuário fornecer várias classes, gere um arquivo consolidado com índice:

```markdown
# Documentação Técnica — {Nome do Módulo ou Sistema}

## Índice

- [ClassName1](#classname1)
- [ClassName2](#classname2)
- ...

---

{Documentação de cada classe seguindo o template wiki acima}
```
