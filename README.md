# Entriloquent

> Entriloquent is a Statamic addon that lets you use entries of any collection similar to how you use Laravel models.

## Features

By creating a PHP class extending `Bastihilger\Entriloquent` you can use this class like a model and have a convenient place for methods concerning this entries of this collection.

## How to Install

You can install this addon via Composer:

``` bash
composer require bastihilger/entriloquent
```

## How to Use

Let's assume you have a Statamic collection called "Products" (having the handle `products`). Now you can create a PHP class anywhere, f.e. in your models directory:

```php
<?php

namespace App\Models;

class Product extends Entriloquent
{
    //
}

```

And use it anywhere in your PHP code like this:

```php
<?php

//returns product entry - will ONLY search in products collection:

$product = Product::find($id);

// also returns product entry, also only searching in products collection:

$product = Product::where('slug', 'awesome-product-1')->first();

```

Let's also assume your `products` collection has a field called `price`. You can do something like this:

```php
<?php

//returns collection of product entries:

$product = Product::where('price', '>', 100)->get();

```
