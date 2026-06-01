# Laravel Best Practices Reference

Source: https://github.com/alexeymezenin/laravel-best-practices

---

## Single Responsibility Principle

Each class should have only one responsibility. Move logging, formatting, and side effects out of controllers.

**Bad:**
```php
public function update(Request $request): string
{
    $validated = $request->validate([...]);
    foreach ($request->events as $event) {
        $date = $this->carbon->parse($event['date'])->toString();
        $this->logger->log('Update event ' . $date);
    }
    $this->event->updateGeneralEvent($request->validated());
    return back();
}
```

**Good:**
```php
public function update(UpdateRequest $request): string
{
    $this->logService->logEvents($request->events);
    $this->event->updateGeneralEvent($request->validated());
    return back();
}
```

---

## Methods Should Do Just One Thing

Break methods that handle multiple responsibilities into smaller, named methods.

**Bad:**
```php
public function getFullNameAttribute(): string
{
    if (auth()->user() && auth()->user()->hasRole('client') && auth()->user()->isVerified()) {
        return 'Mr. ' . $this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name;
    } else {
        return $this->first_name[0] . '. ' . $this->last_name;
    }
}
```

**Good:**
```php
public function getFullNameAttribute(): string
{
    return $this->isVerifiedClient() ? $this->getFullNameLong() : $this->getFullNameShort();
}

public function isVerifiedClient(): bool
{
    return auth()->user() && auth()->user()->hasRole('client') && auth()->user()->isVerified();
}
```

---

## Fat Models, Skinny Controllers

Put all DB-related logic in Eloquent models. Controllers should only orchestrate.

**Bad:**
```php
public function index()
{
    $clients = Client::verified()
        ->with(['orders' => fn($q) => $q->where('created_at', '>', Carbon::today()->subWeek())])
        ->get();
    return view('index', ['clients' => $clients]);
}
```

**Good:**
```php
public function index()
{
    return view('index', ['clients' => $this->client->getWithNewOrders()]);
}

// In Client model:
public function getWithNewOrders(): Collection
{
    return $this->verified()
        ->with(['orders' => fn($q) => $q->where('created_at', '>', Carbon::today()->subWeek())])
        ->get();
}
```

---

## Validation in Form Request Classes

Never validate inside controllers directly. Use dedicated Request classes.

**Bad:**
```php
public function store(Request $request)
{
    $request->validate(['title' => 'required|unique:posts|max:255', 'body' => 'required']);
}
```

**Good:**
```php
public function store(PostRequest $request) { ... }

class PostRequest extends FormRequest
{
    public function rules(): array
    {
        return ['title' => 'required|unique:posts|max:255', 'body' => 'required'];
    }
}
```

---

## Business Logic in Service Classes

Controllers should have only one responsibility. Move business logic to Services.

**Bad:**
```php
public function store(Request $request)
{
    if ($request->hasFile('image')) {
        $request->file('image')->move(public_path('images') . 'temp');
    }
}
```

**Good:**
```php
public function store(Request $request)
{
    $this->articleService->handleUploadedImage($request->file('image'));
}

class ArticleService
{
    public function handleUploadedImage($image): void
    {
        if (!is_null($image)) {
            $image->move(public_path('images') . 'temp');
        }
    }
}
```

---

## Don't Repeat Yourself (DRY)

Use Eloquent scopes, Blade components, and shared logic to avoid duplication.

**Bad:**
```php
public function getActive() {
    return $this->where('verified', 1)->whereNotNull('deleted_at')->get();
}
public function getArticles() {
    return $this->whereHas('user', fn($q) => $q->where('verified', 1)->whereNotNull('deleted_at'))->get();
}
```

**Good:**
```php
public function scopeActive($q) {
    return $q->where('verified', true)->whereNotNull('deleted_at');
}
public function getActive(): Collection { return $this->active()->get(); }
public function getArticles(): Collection {
    return $this->whereHas('user', fn($q) => $q->active())->get();
}
```

---

## Prefer Eloquent Over Raw SQL / Query Builder

Eloquent enables readable, maintainable code with built-in soft deletes, events, and scopes.

**Bad:**
```sql
SELECT * FROM articles WHERE EXISTS (SELECT * FROM users WHERE ...) AND verified = '1' ORDER BY created_at DESC
```

**Good:**
```php
Article::has('user.profile')->verified()->latest()->get();
```

---

## Mass Assignment

Use `create()` with validated data instead of setting attributes one by one.

**Bad:**
```php
$article = new Article;
$article->title = $request->title;
$article->content = $request->content;
$article->save();
```

**Good:**
```php
$category->article()->create($request->validated());
```

---

## Eager Loading (Avoid N+1 Problem)

Never execute queries inside Blade loops. Always eager load relationships.

**Bad (101 queries for 100 users):**
```php
@foreach (User::all() as $user)
    {{ $user->profile->name }}
@endforeach
```

**Good (2 queries):**
```php
// Controller:
$users = User::with('profile')->get();

// Blade:
@foreach ($users as $user)
    {{ $user->profile->name }}
@endforeach
```

---

## Chunk Data for Heavy Tasks

Avoid loading massive datasets into memory at once.

**Bad:**
```php
$users = $this->get();
foreach ($users as $user) { ... }
```

**Good:**
```php
$this->chunk(500, function ($users) {
    foreach ($users as $user) { ... }
});
```

---

## Descriptive Names Over Comments

Prefer self-documenting code with clear method and variable names.

**Bad:**
```php
// Determine if there are any joins
if (count((array) $builder->getQuery()->joins) > 0)
```

**Good:**
```php
if ($this->hasJoins())
```

---

## No JS/CSS in Blade, No HTML in PHP

Keep concerns separated. Pass data via `@json()` or dedicated JS packages.

**Bad:**
```php
let article = `{{ json_encode($article) }}`;
```

**Good:**
```html
<input id="article" type="hidden" value='@json($article)'>
```
```js
let article = $('#article').val();
```

---

## Use Config Files, Language Files, and Constants

Never hardcode strings or env values directly in business logic.

**Bad:**
```php
return $article->type === 'normal';
return back()->with('message', 'Your article has been added!');
$apiKey = env('API_KEY');
```

**Good:**
```php
return $article->type === Article::TYPE_NORMAL;
return back()->with('message', __('app.article_added'));

// config/api.php: 'key' => env('API_KEY')
$apiKey = config('api.key');
```

---

## Use Standard Laravel Tools

Prefer built-in Laravel tools over third-party alternatives:

| Task | Use |
|------|-----|
| Authorization | Policies |
| Asset compilation | Vite / Laravel Mix |
| DB | Eloquent |
| Templates | Blade |
| Form validation | Form Request classes |
| Authentication | Built-in Auth |
| API authentication | Sanctum / Passport |
| Testing | PHPUnit / Pest |
| Task scheduling | Laravel Scheduler |
| Data collections | Laravel Collections |

---

## Naming Conventions

Follow PSR-12 and Laravel community conventions:

| What | Convention | Example |
|------|-----------|---------|
| Controller | Singular | `ArticleController` |
| Route URL | Plural | `articles/1` |
| Route name | snake_case + dot | `users.show_active` |
| Model | Singular | `User` |
| hasOne/belongsTo | Singular | `articleComment` |
| All other relations | Plural | `articleComments` |
| Table | Plural snake_case | `article_comments` |
| Pivot table | Alphabetical singular | `article_user` |
| Table column | snake_case | `meta_title` |
| Foreign key | `{model}_id` | `article_id` |
| Migration | date + description | `2024_01_01_create_articles_table` |
| Method | camelCase | `getAll` |
| Variable | camelCase | `$articlesWithAuthor` |
| View | kebab-case | `show-filtered.blade.php` |
| Config file | snake_case | `google_calendar.php` |
| Interface | Adjective/noun | `AuthenticationInterface` |
| Trait | Adjective | `Notifiable` |
| Enum | Singular | `UserType` |
| FormRequest | Singular | `UpdateUserRequest` |
| Seeder | Singular | `UserSeeder` |

---

## Convention Over Configuration

Respect Laravel defaults so you don't need extra config.

**Bad:**
```php
class Customer extends Model
{
    protected $table = 'Customer';
    protected $primaryKey = 'customer_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}
```

**Good:**
```php
// Table: customers, PK: id — Laravel handles it automatically
class Customer extends Model
{
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }
}
```

---

## Shorter, More Readable Syntax

| Verbose | Preferred |
|---------|-----------|
| `Session::get('cart')` | `session('cart')` |
| `$request->input('name')` | `$request->name` |
| `return Redirect::back()` | `return back()` |
| `is_null($obj->rel) ? null : $obj->rel->id` | `$obj->rel?->id` |
| `return view('index')->with('title', $title)->with('client', $client)` | `return view('index', compact('title', 'client'))` |
| `Carbon::now()` | `now()` |
| `->orderBy('created_at', 'desc')` | `->latest()` |
| `->orderBy('created_at', 'asc')` | `->oldest()` |
| `->first()->name` | `->value('name')` |
| `->where('column', '=', 1)` | `->where('column', 1)` |

---

## Use IoC / Dependency Injection

Inject dependencies via constructor instead of `new Class`.

**Bad:**
```php
$user = new User;
$user->create($request->validated());
```

**Good:**
```php
public function __construct(protected User $user) {}

$this->user->create($request->validated());
```

---

## Dates: Use Casts, Not String Formatting in Blade

**Bad:**
```php
{{ Carbon::createFromFormat('Y-d-m H-i', $object->ordered_at)->toDateString() }}
```

**Good:**
```php
// Model:
protected $casts = ['ordered_at' => 'datetime'];

// Blade:
{{ $object->ordered_at->toDateString() }}
```

---

## Avoid DocBlocks — Use Typed Code Instead

**Bad:**
```php
/**
 * Check if given string is valid ASCII
 * @param string $string
 * @return bool
 */
public function checkString($string) {}
```

**Good:**
```php
public function isValidAsciiString(string $string): bool {}
```

---

## Other Good Practices

- Never put logic in route files
- Minimize vanilla PHP in Blade templates
- Use in-memory DB (SQLite) for tests
- Don't override standard framework features
- Use modern PHP syntax (enums, match, null-safe operator, readonly)
- Avoid View Composers unless truly necessary
- Don't use patterns alien to Laravel (e.g. don't impose Symfony/Spring patterns)
