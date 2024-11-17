<?php

namespace Kraenkvisuell\Entriloquent\Tests;

use Kraenkvisuell\Entriloquent\EntriloquentTestCustomCollectionModel;
use Kraenkvisuell\Entriloquent\EntriloquentTestModel;
use Kraenkvisuell\Entriloquent\EntriloquentTestModelWithMethod;
use Statamic\Facades\Collection;
use Statamic\Facades\Entry;

class ModelTest extends TestCase
{
    protected function setUp():void
    {
        parent::setup();

        $collection = Collection::find('entriloquent_test_models');
        if (!$collection) {
            $collection = Collection::make('entriloquent_test_models');

            $collection->save();
        }

        $customCollection = Collection::find('test_custom_collection');
        if (!$customCollection) {
            $customCollection = Collection::make('test_custom_collection');

            $customCollection->save();
        }

        foreach (Entry::query()->where('collection', 'entriloquent_test_models')->get() as $entry) {
            $entry->delete();
        }
    }

    public function test_that_it_can_be_created(): void
    {
        $testModel = EntriloquentTestModel::create([
            'title' => 'Test Title',
            'slug' => 'test-slug',
        ]);

        $this->assertTrue(
            $testModel->id
            && $testModel->id === Entry::whereCollection('entriloquent_test_models')->first()->id
        );
    }

    public function test_that_it_can_return_a_collections_of_all_entries(): void
    {
        $testModel1 = EntriloquentTestModel::create([
            'title' => 'Test Title',
            'slug' => 'test-slug',
        ]);

        $testModel2 = EntriloquentTestModel::create([
            'title' => 'Test Title 2',
            'slug' => 'test-slug-2',
        ]);

        $collection = EntriloquentTestModel::all();

        $this->assertTrue($collection->count() === 2);
        $this->assertTrue($collection->first()->id === $testModel1->id);
        $this->assertTrue($collection->last()->id === $testModel2->id);
    }

    public function test_that_it_can_return_a_collections_with_2_params(): void
    {
        $testModel1 = EntriloquentTestModel::create([
            'title' => 'Test Title',
            'slug' => 'test-slug',
            'foo' => 'bar',
        ]);

        $testModel2 = EntriloquentTestModel::create([
            'title' => 'Test Title 2',
            'slug' => 'test-slug-2',
            'foo' => 'baz',
        ]);

        $testModel3 = EntriloquentTestModel::create([
            'title' => 'Test Title 3',
            'slug' => 'test-slug-3',
            'foo' => 'bar',
        ]);

        $collection = EntriloquentTestModel::where('foo', 'bar');

        $this->assertTrue($collection->count() === 2);
        $this->assertTrue($collection->first()->id === $testModel1->id);
        $this->assertTrue($collection->last()->id === $testModel3->id);
    }

    public function test_that_it_can_return_a_collections_with_3_params(): void
    {
        $testModel1 = EntriloquentTestModel::create([
            'title' => 'Test Title',
            'slug' => 'test-slug',
            'price' => 1,
        ]);

        $testModel2 = EntriloquentTestModel::create([
            'title' => 'Test Title 2',
            'slug' => 'test-slug-2',
            'price' => 3,
        ]);

        $testModel3 = EntriloquentTestModel::create([
            'title' => 'Test Title 3',
            'slug' => 'test-slug-3',
            'price' => 2,
        ]);

        $collection = EntriloquentTestModel::where('price', '<', 3);

        $this->assertTrue($collection->count() === 2);
        $this->assertTrue($collection->first()->id === $testModel1->id);
        $this->assertTrue($collection->last()->id === $testModel3->id);
    }

    public function test_that_it_can_be_updated(): void
    {
        $testModel = EntriloquentTestModel::create([
            'title' => 'Updated Title',
            'slug' => 'test-slug',
        ]);

        $testModel = $testModel->update(['title' => 'Updated Title', 'slug' => 'updated-slug']);

        $this->assertTrue($testModel->title === 'Updated Title' && $testModel->slug === 'updated-slug');
    }

    public function test_that_custom_method_can_be_called(): void
    {
        $testModel = EntriloquentTestModel::create([
            'title' => 'Test Title',
            'slug' => 'test-slug',
        ]);

        $this->assertTrue($testModel->fooBar() === 'Foo Bar');
    }

    public function test_that_it_has_correct_slug(): void
    {
        $testModel = EntriloquentTestModel::create(['slug' => 'test-slug']);

        $this->assertTrue($testModel->slug && $testModel->slug === Entry::whereCollection('entriloquent_test_models')->first()->slug);
    }

    public function test_that_it_has_correct_title(): void
    {
        $testModel = EntriloquentTestModel::create([
            'title' => 'Test Title',
            'slug' => 'test-slug',
        ]);

        $this->assertTrue($testModel->title && $testModel->title === Entry::whereCollection('entriloquent_test_models')->first()->title);
    }

    public function test_that_it_has_misc_data(): void
    {
        $testModel = EntriloquentTestModel::create([
            'title' => 'Test Title',
            'slug' => 'test-slug',
            'some_other_data' => 'Some Other Data',
        ]);

        $this->assertTrue(
            $testModel->some_other_data
            &&
            $testModel->some_other_data === Entry::whereCollection('entriloquent_test_models')->first()->some_other_data
        );
    }

    public function test_that_it_can_be_found(): void
    {
        $testModel = EntriloquentTestModel::create(['slug' => 'test-slug']);

        $foundModel = EntriloquentTestModel::find($testModel->id);

        $this->assertTrue($testModel->id === $foundModel->id);
    }

    public function test_that_it_can_be_found_by_slug_with_firstWhere(): void
    {
        EntriloquentTestModel::create(['slug' => 'first-slug']);

        $testModel = EntriloquentTestModel::create(['slug' => 'test-slug']);

        $foundModel = EntriloquentTestModel::firstWhere('slug', 'test-slug');

        $this->assertTrue($testModel->id === $foundModel->id);
    }

    public function test_that_it_can_be_found_by_slug_with_where_and_first(): void
    {
        EntriloquentTestModel::create(['slug' => 'first-slug']);

        $testModel = EntriloquentTestModel::create(['slug' => 'test-slug']);

        $foundModel = EntriloquentTestModel::where('slug', 'test-slug')->first();

        $this->assertTrue($testModel->id === $foundModel->id);
    }

    public function test_that_it_can_be_found_by_title(): void
    {
        EntriloquentTestModel::create(['slug' => 'first-slug']);

        $testModel = EntriloquentTestModel::create(['title' => 'Test Title', 'slug' => 'test-slug']);

        $foundModel = EntriloquentTestModel::firstWhere('title', 'Test Title');

        $this->assertTrue($testModel->id === $foundModel->id);
    }

    public function test_that_it_can_be_found_by_misc_data(): void
    {
        EntriloquentTestModel::create(['slug' => 'first-slug']);

        $testModel = EntriloquentTestModel::create(['slug' => 'test-slug', 'some_other_data' => 'Some Other Data']);

        $foundModel = EntriloquentTestModel::firstWhere('some_other_data', 'Some Other Data');

        $this->assertTrue($testModel->id === $foundModel->id);
    }

    public function test_that_it_can_be_created_with_custom_collection(): void
    {
        $testModel = EntriloquentTestCustomCollectionModel::create(['title' => 'Test Title', 'slug' => 'test-slug']);

        $this->assertTrue(
            $testModel->id
            && $testModel->id === Entry::whereCollection('test_custom_collection')->first()->id
        );
    }

    public function test_that_it_can_be_deleted(): void
    {
        $testModel = EntriloquentTestModel::create(['slug' => 'test-slug']);

        $this->assertTrue($testModel !== null);

        $testModel->delete();

        $this->assertTrue(EntriloquentTestModel::find($testModel->id) === null);
    }

    protected function tearDown():void
    {
        foreach (Entry::query()->where('collection', 'entriloquent_test_models')->get() as $entry) {
            $entry->delete();
        }

        foreach (Entry::query()->where('collection', 'test_custom_collection')->get() as $entry) {
            $entry->delete();
        }

        if ($collection = Collection::find('entriloquent_test_models')) {
            $collection->delete();
        }

        if ($collection = Collection::find('test_custom_collection')) {
            $collection->delete();
        }

        parent::tearDown();
    }
}
