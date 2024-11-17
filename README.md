# Entriloquent

> Entriloquent is a Statamic addon that lets you use entries of any collection similar to how you use Laravel models. It is mostly a "convenience" wrapper around "Entry" and mostly makes sense for usage in your PHP/Laravel backend code.

## Features

By creating a PHP class extending `Kraenkvisuell\Entriloquent` you can use this class like a model and have a convenient place for methods concerning this entries of this collection.

## How to Install

You can install this addon via Composer:

``` bash
composer require kraenkvisuell/entriloquent
```

## How to Use

Let's assume you have a Statamic collection called "Products" (having the handle `products`). Now you can create a PHP class anywhere – I personally use the models directory because that makes sense to me, but it could be anything, like f.e. a "Services" directory.

```php
<?php

namespace App\Models;

use Kraenkvisuell\Entriloquent\Entriloquent;

class Product extends Entriloquent
{
    // you can add any methods here that make sense, like...
    public function getPriceInclVat()
    {
        return $this->price * (1 + ($this->vat / 100));
    }
}

```

And use it anywhere in your PHP code like this:

```php
<?php

use App\Models\Product;

//returns product entry - will ONLY search in products collection:

$product = Product::find($id);

// also returns product entry, also only searching in products collection:

$product = Product::where('slug', 'awesome-product-1')->first();
// or
$product = Product::firstWhere('slug', 'awesome-product-1');

// works with any fields on the collection:

$product = Product::where('some_custom_field', 'foo bar baz')->first();
// or
$product = Product::firstWhere('some_custom_field', 'foo bar baz');
```

Let's also assume your `products` collection has a field called `price`. You can do something like this:

```php
<?php

use App\Models\Product;

//returns collection of product entries:

$products = Product::where('price', '>=', 100);

```

> [!IMPORTANT]  
> Entriloquent returns Laravel Collections - this means you don't need to chain a `->get()` onto the `where()` query, it already returns the filtered collection!

## create()

You can create entries like this:

```php
<?php

use App\Models\Product;

$product = Product::create([
    'title' => 'Test Title',
    'slug' => 'test-slug',
    'price' => 99.99,
]);
```

## all()

You can get all entries like this:

```php
<?php

use App\Models\Product;

$products = Product::all();
```

## update()

It comes with a convenient way to update your entries, like this:

```php
<?php

use App\Models\Product;

$product = Product::find($id);

$product = $product->update(['title' => 'Updated Title', 'slug' => 'updated-slug']);

```

## Using a custom class name

By default, it looks for a collection that is the snake case plural version of the class name (`Product` -> `products`, `FooBar` -> `foo_bars`). But you can explicitly define the collection that should be used:



```php
<?php

namespace App\Models;

use Kraenkvisuell\Entriloquent\Entriloquent;

class Post extends Entriloquent
{
    protected static $collectionName = 'blog';
}

```

