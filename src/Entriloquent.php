<?php

namespace Bastihilger\Entriloquent;

use Illuminate\Support\Str;
use Statamic\Facades\Entry;

class Entriloquent
{
    protected static $collection = '';

    public static function create(array $data)
    {
        $slug = Str::slug(Str::random(12));

        if (isset($data['slug']) && trim($data['slug']) !== '') {
            $slug = Str::slug(trim($data['slug']));
        } elseif (isset($data['title']) && trim($data['title']) !== '') {
            $slug = Str::slug($data['title']);
        }

        $entry = Entry::make()->collection(static::getCollection())->slug($slug);

        $entry->data($data);

        $entry->save();

        return $entry;
    }

    public static function where($param, $needle)
    {
        return Entry::query()->where('collection', static::getCollection())->where($param, $needle);
    }

    public static function find($id)
    {
        return Entry::query()->where('collection', static::getCollection())->where('id', $id)->first();
    }

    public static function findOrFail($id)
    {
        return Entry::findOrFail($id);
    }

    public static function findByUri($uri, $site)
    {
        return Entry::findByUri($uri, $site);
    }

    protected static function getCollection()
    {
        if (static::$collection) {
            return static::$collection;
        }

        return Str::plural(Str::snake(substr(strrchr(static::class, '\\'), 1)));
    }
}
