# Livewire v3 — Exemplos de Código

---

## Propriedades corretas vs incorretas

```php
// RUIM — objeto Eloquent na propriedade
class EditPost extends Component
{
    public Post $post; // serializa o modelo inteiro em cada request
}

// BOM — primitivos apenas
class EditPost extends Component
{
    public int $postId;
    public string $title = '';
    public string $body = '';

    public function mount(Post $post): void
    {
        $this->postId = $post->id;
        $this->fill($post->only('title', 'body'));
    }
}
```

---

## Form Objects (Livewire v3)

```php
// app/Livewire/Forms/PostForm.php
class PostForm extends Form
{
    #[Rule('required|min:3|max:255')]
    public string $title = '';

    #[Rule('required')]
    public string $body = '';

    public function save(): Post
    {
        return Post::create($this->all());
    }
}

// app/Livewire/CreatePost.php
class CreatePost extends Component
{
    public PostForm $form;

    public function save(): void
    {
        $this->form->validate();
        $post = $this->form->save();
        $this->dispatch('post-created', id: $post->id);
        $this->redirect(route('posts.index'));
    }
}
```

---

## Computed Properties

```php
class PostList extends Component
{
    public string $search = '';

    #[Computed]
    public function posts(): LengthAwarePaginator
    {
        return Post::query()
            ->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(10);
    }
}
```

```html
{{-- No Blade — computed property é acessada como propriedade normal --}}
@foreach($this->posts as $post)
    <div>{{ $post->title }}</div>
@endforeach
{{ $this->posts->links() }}
```

---

## Eventos entre componentes

```php
// Disparar evento
class CreatePost extends Component
{
    public function save(): void
    {
        $post = Post::create([...]);
        $this->dispatch('post-created', id: $post->id);
    }
}

// Ouvir evento
class PostList extends Component
{
    #[On('post-created')]
    public function handlePostCreated(int $id): void
    {
        // Livewire rerenderiza o componente automaticamente
        // Computed properties são invalidadas
    }
}
```

---

## Loading States

```html
{{-- Botão com estado de carregamento --}}
<button wire:click="save" wire:loading.attr="disabled">
    <span wire:loading.remove wire:target="save">Salvar Orçamento</span>
    <span wire:loading wire:target="save">Salvando...</span>
</button>

{{-- Spinner em seção específica --}}
<div wire:loading wire:target="loadItems" class="text-gray-400">
    Carregando itens...
</div>
<div wire:loading.remove wire:target="loadItems">
    {{-- conteúdo --}}
</div>
```

---

## Proteção de propriedades com #[Locked]

```php
class EditOrcamento extends Component
{
    #[Locked]
    public int $orcamentoId; // cliente não pode alterar o ID

    #[Locked]
    public string $clienteId; // idem

    public string $descricao = '';
    public float $valorTotal = 0;
}
```

---

## Lazy Loading

```php
class TabelaItens extends Component
{
    use WithPagination;

    public function placeholder(): View
    {
        return view('livewire.placeholders.tabela');
    }
}
```

```html
{{-- Carrega o componente pesado apenas quando visível --}}
<livewire:tabela-itens :orcamento-id="$orcamento->id" lazy />
```

---

## Entangle com Alpine.js

```html
<div x-data="{ open: $wire.entangle('modalAberto') }">
    <button @click="open = true">Abrir Modal</button>

    <div x-show="open" @keydown.escape.window="open = false">
        <livewire:form-orcamento />
    </div>
</div>
```

---

## Testes com Livewire

```php
use Livewire\Livewire;

it('cria um orçamento com sucesso', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(CreateOrcamento::class)
        ->set('form.titulo', 'Orçamento Teste')
        ->set('form.cliente_id', $user->id)
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('orcamentos.index'));

    expect(Orcamento::where('titulo', 'Orçamento Teste')->exists())->toBeTrue();
});

it('valida campos obrigatórios', function () {
    Livewire::test(CreateOrcamento::class)
        ->call('save')
        ->assertHasErrors(['form.titulo', 'form.cliente_id']);
});
```

---

## Estrutura de pastas recomendada

```
app/Livewire/
├── Forms/
│   ├── OrcamentoForm.php
│   └── ItemForm.php
├── Orcamentos/
│   ├── Create.php
│   ├── Edit.php
│   └── Index.php
└── Partials/
    └── TabelaItens.php

resources/views/livewire/
├── orcamentos/
│   ├── create.blade.php
│   ├── edit.blade.php
│   └── index.blade.php
└── partials/
    └── tabela-itens.blade.php
```
