---
name: laravel-filament
description: >
  Aplica boas práticas do Filament PHP v4/v5 ao gerar, revisar ou corrigir painéis administrativos Laravel.
  Use esta skill SEMPRE que o usuário mencionar Filament, painel admin, Resources, Form Builder, Table Builder,
  Actions, Widgets, RelationManagers, painéis CRUD, ou qualquer interface administrativa gerada com Filament.
  Acione também quando o usuário quiser criar telas de gerenciamento (clientes, produtos, relatórios, usuários)
  ou perguntar como fazer algo no painel admin do Laravel. Aplique mesmo sem menção explícita ao Filament.
---

# Laravel Filament v4/v5 — Boas Práticas

Referência: https://filamentphp.com/docs | Guia completo em `references/filament.md`

> **Versões:** Filament v5 é idêntico ao v4 em API — a única diferença é suporte ao Livewire v4.
> Para Laravel 12 novo, use v5. Para projetos com Livewire v3, use v4.

---

## Instalação

```bash
# v5 (Livewire v4, Laravel 12+)
composer require filament/filament:"^5.0"

# v4 (Livewire v3)
composer require filament/filament:"^4.0"

php artisan filament:install --panels
php artisan make:filament-user
```

---

## Estrutura de Arquivos (v4/v5)

Em v4/v5, Resources têm **arquivos separados** para Schema e Table — diferente do v3 que colocava tudo no Resource:

```
app/Filament/Resources/OrcamentoResource/
├── OrcamentoResource.php
├── Pages/
│   ├── CreateOrcamento.php
│   ├── EditOrcamento.php
│   └── ListOrcamentos.php
├── Schemas/
│   └── OrcamentoForm.php       ← formulário aqui
├── Tables/
│   └── OrcamentosTable.php     ← tabela aqui
└── RelationManagers/
    └── ItensRelationManager.php
```

---

## Criando Resources

```bash
# Gera resource com form e table automaticamente baseado no modelo
php artisan make:filament-resource Orcamento --generate

# Com soft deletes
php artisan make:filament-resource Orcamento --generate --soft-deletes

# Simples (apenas página de listagem com modais, sem Edit/Create separados)
php artisan make:filament-resource Orcamento --simple
```

---

## Mudanças v3 → v4/v5 (críticas)

| v3 | v4/v5 |
|----|-------|
| `->actions([])` | `->recordActions([])` |
| `->bulkActions([])` | `->toolbarActions([])` |
| `->reactive()` | `->live()` |
| TinyMCE | Tiptap |
| Form/Table no Resource | Schemas/ e Tables/ separados |
| Namespace `Filament\Tables\Actions` | `Filament\Actions` (unificado) |

---

## Formulários (Schema)

```php
use Filament\Schemas\Schema;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

public static function configure(Schema $schema): Schema
{
    return $schema->components([
        Section::make('Dados do Orçamento')
            ->schema([
                TextInput::make('titulo')->required()->maxLength(255),
                Select::make('cliente_id')
                    ->relationship('cliente', 'nome')
                    ->searchable()
                    ->required(),
            ])
            ->columns(2),
    ]);
}
```

**Campos condicionais com `live()`:**
```php
Select::make('status')
    ->options([...])
    ->live(), // dispara update no servidor

DatePicker::make('data_validade')
    ->visible(fn (Get $get): bool => $get('status') === 'pendente'),
```

**Para show/hide simples sem roundtrip, use `visibleJs()`:**
```php
TextInput::make('desconto')
    ->visibleJs('$get("tipo_desconto") === "percentual"'),
```

---

## Tabelas (Table)

```php
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

public static function configure(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('titulo')->searchable()->sortable(),
            TextColumn::make('cliente.nome')->searchable(),
            TextColumn::make('total')->money('BRL')->sortable(),
            TextColumn::make('status')->badge()
                ->color(fn (string $state): string => match ($state) {
                    'aprovado' => 'success',
                    'pendente' => 'warning',
                    'recusado' => 'danger',
                }),
        ])
        ->recordActions([EditAction::make()])        // ações por linha
        ->toolbarActions([                            // ações em bulk
            BulkActionGroup::make([DeleteBulkAction::make()]),
        ])
        ->defaultSort('created_at', 'desc');
}
```

---

## Relacionamentos

**Repeater** para hasMany simples no formulário:
```php
Repeater::make('itens')
    ->relationship('itens')
    ->schema([
        TextInput::make('descricao')->required(),
        TextInput::make('quantidade')->numeric()->required(),
        TextInput::make('valor_unitario')->numeric()->prefix('R$'),
    ])
    ->itemLabel(fn (array $state): ?string => $state['descricao'] ?? null)
    ->addActionLabel('Adicionar Item')
    ->collapsible(),
```

**RelationManager** para relacionamentos complexos:
```bash
php artisan make:filament-relation-manager OrcamentoResource itens descricao
```

Registre no Resource:
```php
public static function getRelations(): array
{
    return [RelationManagers\ItensRelationManager::class];
}
```

---

## Performance

**Eager loading** no Resource para evitar N+1:
```php
public static function getEloquentQuery(): Builder
{
    return parent::getEloquentQuery()->with(['cliente', 'itens'])->withCount('itens');
}
```

**Cache em colunas calculadas:**
```php
TextColumn::make('total_calculado')
    ->state(fn ($record): string =>
        Cache::remember("orcamento.{$record->id}.total", now()->addHour(),
            fn () => $record->itens()->sum('subtotal')
        )
    )
    ->money('BRL'),
```

---

## Actions Customizadas

```php
use Filament\Actions\Action;
use Filament\Notifications\Notification;

Action::make('gerarPdf')
    ->label('Gerar PDF')
    ->icon('heroicon-o-document')
    ->action(function ($record): void {
        GenerarPdfJob::dispatch($record);
        Notification::make()->title('PDF sendo gerado')->success()->send();
    })
    ->requiresConfirmation(),
```

---

## Multi-tenancy

Para aplicações com múltiplos clientes/empresas:
```php
// No PanelProvider
->tenant(Empresa::class)
```

---

## Checklist de Revisão

- [ ] Usando sintaxe v3 (`actions()`, `bulkActions()`, `reactive()`)? → atualizar para v4/v5
- [ ] Form e Table no Resource.php? → mover para Schemas/ e Tables/
- [ ] N+1 nas colunas relacionais? → eager load em `getEloquentQuery()`
- [ ] `live()` onde só precisa de show/hide? → usar `visibleJs()` / `hiddenJs()`
- [ ] Actions customizadas pesadas sem job? → dispatch para fila
- [ ] Seções sem título nas forms? → agrupar com `Section::make('...')`
- [ ] Coluna calculada pesada? → cachear com `Cache::remember()`

---

## Referências

Para exemplos completos e casos avançados:
→ `references/filament.md`
