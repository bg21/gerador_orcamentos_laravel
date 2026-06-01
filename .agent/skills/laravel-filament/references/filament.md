# Filament v4/v5 — Exemplos Avançados

---

## Resource completo — OrcamentoResource

```php
// app/Filament/Resources/OrcamentoResource.php
class OrcamentoResource extends Resource
{
    protected static ?string $model = Orcamento::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocument;
    protected static ?string $navigationGroup = 'Comercial';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return OrcamentoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrcamentosTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['cliente', 'itens'])
            ->withCount('itens');
    }

    public static function getRelations(): array
    {
        return [RelationManagers\ItensRelationManager::class];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListOrcamentos::route('/'),
            'create' => Pages\CreateOrcamento::route('/create'),
            'edit'   => Pages\EditOrcamento::route('/{record}/edit'),
        ];
    }
}
```

---

## Schema (Formulário) completo

```php
// Schemas/OrcamentoForm.php
class OrcamentoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informações do Orçamento')
                ->schema([
                    TextInput::make('titulo')
                        ->required()
                        ->maxLength(255)
                        ->columnSpan(2),

                    Select::make('cliente_id')
                        ->relationship('cliente', 'nome')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->createOptionForm([
                            TextInput::make('nome')->required(),
                            TextInput::make('email')->email()->required(),
                        ]),

                    Select::make('status')
                        ->options([
                            'rascunho'  => 'Rascunho',
                            'enviado'   => 'Enviado',
                            'aprovado'  => 'Aprovado',
                            'recusado'  => 'Recusado',
                        ])
                        ->default('rascunho')
                        ->required()
                        ->live(),

                    DatePicker::make('data_validade')
                        ->required()
                        ->visible(fn (Get $get): bool =>
                            in_array($get('status'), ['enviado', 'aprovado'])
                        ),
                ])
                ->columns(2),

            Section::make('Itens')
                ->schema([
                    Repeater::make('itens')
                        ->relationship('itens')
                        ->schema([
                            TextInput::make('descricao')
                                ->required()
                                ->columnSpan(2),
                            TextInput::make('quantidade')
                                ->numeric()
                                ->default(1)
                                ->required()
                                ->live()
                                ->afterStateUpdated(fn ($state, Set $set, Get $get) =>
                                    $set('subtotal', $state * $get('valor_unitario'))
                                ),
                            TextInput::make('valor_unitario')
                                ->numeric()
                                ->prefix('R$')
                                ->required()
                                ->live()
                                ->afterStateUpdated(fn ($state, Set $set, Get $get) =>
                                    $set('subtotal', $state * $get('quantidade'))
                                ),
                            TextInput::make('subtotal')
                                ->numeric()
                                ->prefix('R$')
                                ->disabled()
                                ->dehydrated(),
                        ])
                        ->columns(4)
                        ->itemLabel(fn (array $state): ?string => $state['descricao'] ?? null)
                        ->addActionLabel('Adicionar Item')
                        ->collapsible()
                        ->reorderable(),
                ]),

            Section::make('Observações')
                ->schema([
                    Textarea::make('observacoes')
                        ->rows(3)
                        ->columnSpanFull(),
                ])
                ->collapsible()
                ->collapsed(),
        ]);
    }
}
```

---

## Table completa com filtros e ações

```php
// Tables/OrcamentosTable.php
class OrcamentosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('titulo')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record): string => "Cliente: {$record->cliente->nome}"),

                TextColumn::make('itens_count')
                    ->label('Itens')
                    ->counts('itens')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('total')
                    ->money('BRL')
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'aprovado' => 'success',
                        'enviado'  => 'info',
                        'rascunho' => 'gray',
                        'recusado' => 'danger',
                        default    => 'gray',
                    }),

                TextColumn::make('data_validade')
                    ->date('d/m/Y')
                    ->sortable()
                    ->color(fn ($record): string =>
                        $record->data_validade?->isPast() ? 'danger' : 'success'
                    ),

                TextColumn::make('created_at')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'rascunho'  => 'Rascunho',
                        'enviado'   => 'Enviado',
                        'aprovado'  => 'Aprovado',
                        'recusado'  => 'Recusado',
                    ]),

                SelectFilter::make('cliente')
                    ->relationship('cliente', 'nome')
                    ->searchable()
                    ->preload(),

                TernaryFilter::make('vencido')
                    ->label('Validade')
                    ->placeholder('Todos')
                    ->trueLabel('Vencidos')
                    ->falseLabel('Vigentes')
                    ->queries(
                        true:  fn ($query) => $query->where('data_validade', '<', now()),
                        false: fn ($query) => $query->where('data_validade', '>=', now()),
                    ),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('gerarPdf')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(fn ($record) => GenerarPdfJob::dispatch($record))
                    ->requiresConfirmation()
                    ->modalHeading('Gerar PDF'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    Action::make('enviarSelecionados')
                        ->label('Marcar como Enviado')
                        ->icon('heroicon-o-paper-airplane')
                        ->action(fn (Collection $records) =>
                            $records->each->update(['status' => 'enviado'])
                        )
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
```

---

## RelationManager — Itens do Orçamento

```php
class ItensRelationManager extends RelationManager
{
    protected static string $relationship = 'itens';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('descricao')->required()->columnSpanFull(),
            TextInput::make('quantidade')->numeric()->required()->default(1),
            TextInput::make('valor_unitario')->numeric()->prefix('R$')->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('descricao')
            ->columns([
                TextColumn::make('descricao')->searchable(),
                TextColumn::make('quantidade'),
                TextColumn::make('valor_unitario')->money('BRL'),
                TextColumn::make('subtotal')
                    ->state(fn ($record) => $record->quantidade * $record->valor_unitario)
                    ->money('BRL'),
            ])
            ->headerActions([CreateAction::make()])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
```

---

## Widget de Dashboard

```php
// app/Filament/Widgets/OrcamentosOverview.php
class OrcamentosOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total de Orçamentos', Orcamento::count())
                ->description('Todos os orçamentos')
                ->color('primary'),

            Stat::make('Aprovados este mês',
                Orcamento::where('status', 'aprovado')
                    ->whereMonth('updated_at', now()->month)
                    ->count()
            )
                ->description('Mês atual')
                ->color('success'),

            Stat::make('Valor Total Aprovado',
                'R$ ' . number_format(
                    Orcamento::where('status', 'aprovado')->sum('total'), 2, ',', '.'
                )
            )
                ->color('success'),
        ];
    }
}
```

---

## Autorização com Policies

```php
// No Resource — Filament respeita Policies automaticamente
// Basta criar a Policy e registrar no AuthServiceProvider

class OrcamentoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin', 'vendedor']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['admin', 'vendedor']);
    }

    public function update(User $user, Orcamento $orcamento): bool
    {
        return $user->hasRole('admin') || $orcamento->user_id === $user->id;
    }

    public function delete(User $user, Orcamento $orcamento): bool
    {
        return $user->hasRole('admin');
    }
}
```

---

## Multi-tenancy (por empresa)

```php
// app/Providers/Filament/AdminPanelProvider.php
public function panel(Panel $panel): Panel
{
    return $panel
        ->tenant(Empresa::class)
        ->tenantRoutePrefix('empresa')
        ->tenantMenuItems([
            MenuItem::make()
                ->label('Configurações')
                ->url(fn (): string => route('filament.pages.empresa-settings'))
                ->icon('heroicon-o-cog'),
        ]);
}
```
