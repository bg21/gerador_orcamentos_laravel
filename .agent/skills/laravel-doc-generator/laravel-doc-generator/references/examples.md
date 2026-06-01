# Exemplos de Saída por Tipo de Classe

## Exemplo 1 — Model com PHPDoc

**Entrada (código PHP):**
```php
class Order extends Model
{
    protected $fillable = ['user_id', 'status', 'total', 'notes'];
    protected $casts = ['total' => 'decimal:2', 'created_at' => 'datetime'];

    public function user() { return $this->belongsTo(User::class); }
    public function items() { return $this->hasMany(OrderItem::class); }

    public function scopePending($query) { return $query->where('status', 'pending'); }
    public function scopeForUser($query, int $userId) { return $query->where('user_id', $userId); }

    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
        event(new OrderCancelled($this));
    }
}
```

**Saída PHPDoc:**
```php
/**
 * Representa um pedido realizado por um usuário no sistema.
 *
 * Gerencia o ciclo de vida do pedido (pending → processing → completed / cancelled)
 * e dispara eventos para notificações quando o status muda.
 *
 * @package App\Models
 */
class Order extends Model
{
    /** @var array<int, string> Campos que podem ser atribuídos em massa */
    protected $fillable = ['user_id', 'status', 'total', 'notes'];

    protected $casts = ['total' => 'decimal:2', 'created_at' => 'datetime'];

    /**
     * Usuário que realizou o pedido.
     *
     * @return BelongsTo<User, Order>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Itens incluídos neste pedido.
     *
     * @return HasMany<OrderItem>
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Filtra pedidos com status "pending" (aguardando processamento).
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    /**
     * Filtra pedidos pertencentes a um usuário específico.
     *
     * @param  Builder  $query
     * @param  int  $userId  ID do usuário
     * @return Builder
     */
    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Cancela o pedido e notifica o sistema via evento.
     *
     * Atualiza o status para "cancelled" e dispara o evento
     * {@see OrderCancelled} para que listeners possam reagir
     * (ex: estornar pagamento, enviar email ao cliente).
     *
     * @return void
     * @throws \Exception Se o pedido já estiver com status "completed"
     */
    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
        event(new OrderCancelled($this));
    }
}
```

---

## Exemplo 2 — Service com README.md

**Entrada:**
```php
class PaymentService
{
    public function __construct(
        private StripeClient $stripe,
        private OrderRepository $orders,
    ) {}

    public function charge(Order $order, string $paymentMethodId): PaymentIntent
    {
        $intent = $this->stripe->paymentIntents->create([
            'amount' => $order->total * 100,
            'currency' => 'brl',
            'payment_method' => $paymentMethodId,
            'confirm' => true,
        ]);
        $this->orders->markAsPaid($order, $intent->id);
        return $intent;
    }

    public function refund(string $paymentIntentId, ?int $amountCents = null): Refund
    {
        return $this->stripe->refunds->create([
            'payment_intent' => $paymentIntentId,
            'amount' => $amountCents,
        ]);
    }
}
```

**Saída README.md:**
```markdown
# PaymentService

> Processa cobranças e estornos de pedidos via API do Stripe.

---

## Visão Geral

Responsável por toda a comunicação com a API do Stripe para cobranças e reembolsos.
Não contém lógica de validação de pedidos — assume que o `Order` recebido já está
validado e pronto para cobrança. Após uma cobrança bem-sucedida, atualiza o status
do pedido via `OrderRepository`.

---

## Dependências

| Dependência | Tipo | Papel |
|-------------|------|-------|
| `StripeClient` | SDK externo | Comunicação com a API do Stripe |
| `OrderRepository` | Repository | Atualiza status e dados do pedido após cobrança |

---

## Métodos Públicos

### `charge(Order $order, string $paymentMethodId): PaymentIntent`

Realiza a cobrança imediata de um pedido usando um método de pagamento previamente
tokenizado pelo frontend (Stripe.js).

**Parâmetros:**
- `$order` — `Order` — O pedido a ser cobrado; o valor é lido de `$order->total`
- `$paymentMethodId` — `string` — ID do método de pagamento (ex: `pm_1Abc...`), obtido via Stripe Elements

**Retorno:** `PaymentIntent` — Objeto do Stripe com ID, status e demais dados da cobrança

**Side-effects:** Marca o pedido como pago no banco de dados via `OrderRepository::markAsPaid()`

---

### `refund(string $paymentIntentId, ?int $amountCents = null): Refund`

Solicita estorno total ou parcial de uma cobrança já processada.

**Parâmetros:**
- `$paymentIntentId` — `string` — ID do `PaymentIntent` a ser estornado
- `$amountCents` — `int|null` — Valor em centavos a estornar; `null` para estorno total

**Retorno:** `Refund` — Objeto do Stripe com dados do estorno criado

**Side-effects:** Nenhum — a atualização do status do pedido deve ser feita pelo caller via evento ou diretamente

---

## Exemplos de Uso

```php
// Cobrança ao finalizar um pedido
$paymentIntent = app(PaymentService::class)->charge($order, $request->payment_method_id);

// Estorno parcial de R$ 10,00
$refund = app(PaymentService::class)->refund($paymentIntentId, 1000);

// Estorno total
$refund = app(PaymentService::class)->refund($paymentIntentId);
```

---

## Notas e Avisos

- O valor enviado ao Stripe é sempre em **centavos** (`$order->total * 100`); certifique-se de que `total` está em reais.
- Erros do Stripe lançam `\Stripe\Exception\ApiErrorException` — trate no caller.
- O método `refund()` **não atualiza** o status do pedido no banco; isso é responsabilidade do caller.
```

---

## Exemplo 3 — FormRequest com Wiki/Notion

**Entrada:**
```php
class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:99'],
            'notes' => ['nullable', 'string', 'max:500'],
            'coupon_code' => ['nullable', 'string', 'exists:coupons,code'],
        ];
    }
}
```

**Saída Wiki/Notion:**
```markdown
# 📄 StoreOrderRequest

**Tipo:** FormRequest  
**Namespace:** `App\Http\Requests\StoreOrderRequest`  
**Arquivo:** `app/Http/Requests/StoreOrderRequest.php`

---

## 🎯 Propósito

Valida os dados de entrada para a criação de um novo pedido. Garante que o carrinho
contenha ao menos um item válido e que produtos e cupons referenciados existam no
banco de dados antes de qualquer processamento.

---

## 🔐 Autorização

Apenas usuários autenticados podem realizar esta requisição. Usuários convidados
(não autenticados) recebem `403 Forbidden`.

---

## ✅ Regras de Validação

| Campo | Regras | Descrição |
|-------|--------|-----------|
| `items` | `required, array, min:1` | O carrinho é obrigatório e deve conter ao menos 1 item |
| `items.*.product_id` | `required, integer, exists:products,id` | Cada item deve referenciar um produto existente |
| `items.*.quantity` | `required, integer, min:1, max:99` | Quantidade por item: mínimo 1, máximo 99 |
| `notes` | `nullable, string, max:500` | Observações do pedido, opcionais, até 500 caracteres |
| `coupon_code` | `nullable, string, exists:coupons,code` | Código de cupom opcional; deve existir na tabela `coupons` |

---

## 💡 Exemplo de Payload Válido

```json
{
  "items": [
    { "product_id": 12, "quantity": 2 },
    { "product_id": 7, "quantity": 1 }
  ],
  "notes": "Entregar após as 18h",
  "coupon_code": "DESCONTO10"
}
```

---

## ⚠️ Avisos

- A validação `exists:products,id` **não verifica** se o produto está ativo/disponível — isso deve ser feito na camada de Service.
- Cupons expirados que ainda existem na tabela passarão na validação; valide a data de expiração no `OrderService`.
```
