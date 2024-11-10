# Entriloquent

> Entriloquent is a Statamic addon that lets you use entries of any collection similar to how you use Laravel models.

## Features

By creating a PHP class extending `Kraenkvisuell\Entriloquent` you can use this class like a model and have a convenient place for methods concerning this entries of this collection.

## How to Install

You can install this addon via Composer:

``` bash
composer require kraenkvisuell/entriloquent
```

## How to Use

Let's assume you have a Statamic collection called "Products" (having the handle `products`). Now you can create a PHP class anywhere, f.e. in your models directory:

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

```

Let's also assume your `products` collection has a field called `price`. You can do something like this:

```php
<?php

use App\Models\Product;

//returns collection of product entries:

$product = Product::where('price', '>=', 100)->get();

```
