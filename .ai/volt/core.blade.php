- This project uses Livewire Volt for interactivity within its pages. New pages requiring interactivity must also use Livewire Volt. There is documentation available for it.
- Volt is an elegantly crafted **functional** API for Livewire that supports single-file components, allowing a component's PHP logic and Blade templates to coexist in the same file
- **Single-File Components**: Livewire Volt allows PHP logic and Blade templates in one file. Components use the `@volt` directive.
- You must check existing Volt components to find out if they're functional or class based. If you can't detect that, ask the user which they prefer before writing a Volt component.


## Volt Functional Component Example
@verbatim
<code-snippet name="Volt Functional Component Example" lang="php">
@volt
<?php
use function Livewire\Volt\{state, computed};

state(['count' => 0]);

$increment = fn () => $this->count++;
$decrement = fn () => $this->count--;

$double = computed(fn () => $this->count * 2);
?>

<div>
    <h1>Count: {{ $count }}</h1>
    <h2>Double: {{ $this->double }}</h2>
    <button wire:click="increment">+</button>
    <button wire:click="decrement">-</button>
</div>
@endvolt
</code-snippet>
@endverbatim

## Volt Class based Component Example
To get started, define an anonymous class that extends Livewire\Volt\Component. Within the class, you may utilize all of the features of Livewire using traditional Livewire syntax:

@verbatim
<code-snippet name="Volt Class-based Volt Component Example" lang="php">
use Livewire\Volt\Component;

new class extends Component {
    public $count = 0;

    public function increment()
    {
        $this->count++;
    }
} ?>

<div>
    <h1>{{ $count }}</h1>
    <button wire:click="increment">+</button>
</div>
</code-snippet>
@endverbatim

### Testing Volt & Volt Components
- Use the existing location if tests already exist, otherwise fallback to `tests/Feature/Volt`

<code-snippet name="Livewire Test Example" lang="php">
use Livewire\Volt\Volt;

test('counter increments', function () {
    Volt::test('counter')
        ->assertSee('Count: 0')
        ->call('increment')
        ->assertSee('Count: 1');
});
</code-snippet>

@verbatim
<code-snippet name="Volt component test using Pest" lang="php">
declare(strict_types=1);

use App\Models\{User, Product};
use Livewire\Volt\Volt;

test('product form creates product', function () {
    $user = User::factory()->create();

    Volt::test('pages.products.create')
        ->actingAs($user)
        ->set('form.name', 'Test Product')
        ->set('form.description', 'Test Description')
        ->set('form.price', 99.99)
        ->call('create')
        ->assertHasNoErrors();

    expect(Product::where('name', 'Test Product')->exists())->toBeTrue();
});
</code-snippet>
@endverbatim

## Common Patterns

@verbatim
<code-snippet name="CRUD with Volt" lang="php">
@volt
use App\Models\Product;
use function Livewire\Volt\{state, computed};

state(['editing' => null, 'search' => '']);

$products = computed(fn() => Product::when($this->search,
    fn($q) => $q->where('name', 'like', "%{$this->search}%")
)->get());

$edit = fn(Product $product) => $this->editing = $product->id;
$delete = fn(Product $product) => $product->delete();
?>

    <!-- UI here -->
@endvolt
</code-snippet>
@endverbatim

@verbatim
<code-snippet name="Real-time search with Volt" lang="php">
    <flux:input
        wire:model.live.debounce.300ms="search"
        placeholder="Search..."
    />
</code-snippet>
@endverbatim


@verbatim
<code-snippet name="Loading states with Volt" lang="php">
    <flux:button wire:click="save" wire:loading.attr="disabled">
        <span wire:loading.remove>Save</span>
        <span wire:loading>Saving...</span>
    </flux:button>
</code-snippet>
@endverbatim
