<?php

namespace Kraenkvisuell\Entriloquent;

use Illuminate\Support\Str;
use Statamic\Facades\Entry;

class Entriloquent
{
    protected static $collectionName = '';

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

        return static::augment($entry);
    }

    public static function firstWhere($param, $needle)
    {
        $entry = Entry::query()->where('collection', static::getCollection())->where($param, $needle)->first();

        return $entry ? static::augment($entry) : null;
    }

    public static function find($id)
    {
        $entry = Entry::query()->where('collection', static::getCollection())->where('id', $id)->first();

        return $entry ? static::augment($entry) : null;
    }

    public static function all()
    {
        $entries = Entry::query()->where('collection', static::getCollection())->get();

        return $entries->map(fn ($entry) =>  static::augment($entry));
    }

    public static function where($param1, $param2, $param3 = null)
    {
        $query = Entry::query()->where('collection', static::getCollection())->where($param1, $param2, $param3);

        $entries = $query->get();

        return $entries->map(fn ($entry) =>  static::augment($entry));
    }

    public function delete()
    {
        $entry = Entry::find($this->id);

        return $entry ? $entry->delete() : false;
    }

    public function update(array $data)
    {
        $entry = Entry::find($this->id);

        if (!$entry) {
            return null;
        }

        $newData = [];

        foreach ($data as $key => $value) {
            if ($key === 'slug') {
                $entry->slug($value);
            } else {
                $newData[$key] = $value;
            }
        }

        $entry->data($newData);

        $entry->save();

        return $this->find($entry->id);
    }

    protected static function augment($entry)
    {
        $model = new static();

        foreach ($entry->toArray() as $key => $value) {
            if (!isset($model->{$key})) {
                $model->{$key} = $value;
            }
        }

        return $model;
    }

    protected static function getCollection()
    {
        if (static::$collectionName) {
            return static::$collectionName;
        }

        return Str::plural(Str::snake(substr(strrchr(static::class, '\\'), 1)));
    }
}
