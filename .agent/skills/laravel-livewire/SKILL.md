---
name: laravel-livewire
description: >
  Aplica boas práticas do Laravel Livewire v3 ao gerar, revisar ou corrigir componentes Livewire.
  Use esta skill SEMPRE que o usuário mencionar Livewire, wire:model, wire:click, componentes reativos,
  formulários dinâmicos sem JavaScript, computed properties, lifecycle hooks, eventos entre componentes,
  loading states, lazy loading, ou qualquer interface interativa construída com Livewire no Laravel.
  Acione também quando o usuário perguntar como fazer algo "sem JS" ou "só com PHP" em interfaces dinâmicas.
---

# Laravel Livewire v3 — Boas Práticas

Referências: https://github.com/michael-rubel/livewire-best-practices | https://livewire.laravel.com/docs

Para exemplos detalhados de código, consulte `references/livewire.md`.

---

## A Regra de Ouro

```
Nunca passe objetos grandes para propriedades públicas de componentes Livewire.
```

Livewire serializa/desserializa o estado do componente em cada request. Objetos pesados (como modelos Eloquent inteiros) aumentam drasticamente o payload e degradam a performance. Use apenas tipos primitivos nas propriedades: `string`, `int`, `bool`, `array`.

---

## Estrutura de Componentes

**Sempre use um único elemento raiz** no template Blade do componente:
```html
<div> <!-- sempre um único elemento raiz -->
    ...
</div>
```

**Profundidade máxima de nesting: 1.** Um componente Livewire pode conter outro (nível 1), mas nunca aninhe além disso. Para nesting mais profundo, use Blade components (sem overhead Livewire).

**Crie, mova e renomeie via Artisan:**
```bash
php artisan make:livewire NomeDoComponente
php artisan livewire:move Old/Path New/Path
```

---

## Propriedades e Estado

- Propriedades públicas = primitivos (`string`, `int`, `bool`, `array`)
- Nunca atribua modelos Eloquent diretamente a propriedades
- Use `#[Locked]` para proteger propriedades sensíveis de manipulação client-side
- Em full-page components, busque os dados no próprio componente e passe primitivos para os filhos
- Use Route Model Binding no `mount()` para buscar o modelo, mapeie só os atributos necessários:

```php
public function mount(Post $post): void
{
    $this->fill($post->only('title', 'body', 'status'));
}
```

---

## Formulários

**Use Form Objects** (introduzidos no Livewire v3) — deixa o componente mais limpo e reutilizável:
```php
class PostForm extends Form
{
    #[Rule('required|min:3')]
    public string $title = '';

    #[Rule('required')]
    public string $body = '';
}
```

**Reutilize regras de validação** do Form Request:
```php
public function rules(): array
{
    return (new StorePostRequest)->rules();
}
```

**Evite `wire:model.live`** sempre que possível — cada keystroke dispara um request ao servidor. Em Livewire v3, `wire:model` já é `defer` por padrão, o que é o comportamento correto.

---

## Performance

**Computed properties** para acessar banco — são cacheadas no ciclo de vida do componente:
```php
#[Computed]
public function posts(): Collection
{
    return Post::where('user_id', $this->userId)->get();
}
```

**Evite polling** — use event listeners em vez de `wire:poll`. Polling dispara requests constantes, mesmo sem dados novos:
```php
// ruim
<div wire:poll.5s>...</div>

// melhor
#[On('post-updated')]
public function refreshPosts(): void { ... }
```

**Lazy loading** para componentes pesados:
```php
<livewire:heavy-table lazy />
```

---

## UX

**Sempre use loading states** para ações que demoram — evita que o usuário clique várias vezes:
```html
<button wire:click="save" wire:loading.attr="disabled">
    <span wire:loading.remove>Salvar</span>
    <span wire:loading>Salvando...</span>
</button>
```

**Use `wire:key`** em loops para garantir DOM diffing correto:
```html
@foreach($items as $item)
    <livewire:item-card :item="$item" :key="$item->id" />
@endforeach
```

**`$wire.entangle`** para sincronizar estado com Alpine.js sem roundtrip extra:
```js
let open = $wire.entangle('modalOpen');
```

---

## Segurança

- Nunca exponha dados sensíveis em propriedades públicas sem `#[Locked]`
- Valide sempre no servidor — o cliente pode alterar qualquer propriedade pública
- Use `#[Locked]` em IDs e campos que o usuário não deve modificar diretamente

---

## Lifecycle Hooks

| Hook | Quando usar |
|------|------------|
| `mount()` | Inicialização, busca de dados iniciais |
| `updated($field)` | Reagir a mudança de uma propriedade específica |
| `hydrate()` | Executar lógica em cada request subsequente |
| `rendering()` | Antes de renderizar o Blade |
| `rendered()` | Após o DOM ser atualizado |

---

## Checklist de Revisão

- [ ] Propriedade pública recebe modelo Eloquent? → mapear para primitivos
- [ ] Nesting mais de 1 nível? → substituir por Blade component
- [ ] Usando `wire:model.live` desnecessariamente? → remover `.live`
- [ ] Usando `wire:poll`? → substituir por event listeners
- [ ] Query SQL dentro de método chamado no Blade? → converter para computed property
- [ ] Dados sensíveis em propriedade pública? → adicionar `#[Locked]`
- [ ] Formulário sem Form Object? → extrair para Form Object
- [ ] Ação longa sem loading state? → adicionar `wire:loading`
- [ ] Loop sem `wire:key`? → adicionar `:key="$item->id"`
- [ ] Testes escritos para o componente? → usar `Livewire::test()`

---

## Referências

Para exemplos completos de código de cada prática:
→ `references/livewire.md`
