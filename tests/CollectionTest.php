<?php

namespace Sunrise\Collection\Tests;

use PHPUnit\Framework\TestCase;
use Sunrise\Collection\Collection;
use Sunrise\Collection\CollectionInterface;

class CollectionTest extends TestCase
{
	public function testConstructor()
	{
		$collection = new Collection();

		$this->assertCollectionStoreEquals($collection, []);

		$this->assertInstanceOf(CollectionInterface::class, $collection);
	}

	public function testConstructorWithData()
	{
		$collection = new Collection([0]);

		$this->assertCollectionStoreEquals($collection, [0]);
	}

	public function testAdd()
	{
		$collection = new Collection();

		$this->assertInstanceOf(CollectionInterface::class, $collection->add(0));
		$this->assertCollectionStoreEquals($collection, [0]);

		$this->assertInstanceOf(CollectionInterface::class, $collection->add(1));
		$this->assertCollectionStoreEquals($collection, [0, 1]);

		$this->assertInstanceOf(CollectionInterface::class, $collection->add(2));
		$this->assertCollectionStoreEquals($collection, [0, 1, 2]);
	}

	public function testSet()
	{
		$collection = new Collection();

		$this->assertInstanceOf(CollectionInterface::class, $collection->set(0, 0));
		$this->assertCollectionStoreEquals($collection, [0]);

		$this->assertInstanceOf(CollectionInterface::class, $collection->set(0, 1));
		$this->assertCollectionStoreEquals($collection, [1]);

		$this->assertInstanceOf(CollectionInterface::class, $collection->set(1, 2));
		$this->assertCollectionStoreEquals($collection, [1, 2]);
	}

	public function testGet()
	{
		$collection = new Collection();

		$this->assertEquals(0, $collection->add(0)->get(0));

		$this->assertEquals(null, $collection->set(0, null)->get(0, false));

		$this->assertEquals(null, $collection->get(1));

		$this->assertEquals(false, $collection->get(1, false));
	}

	public function testRemove()
	{
		$collection = new Collection();

		$this->assertEquals(0, $collection->add(0)->remove(0));
		$this->assertCollectionStoreEquals($collection, []);

		$this->assertEquals(null, $collection->set(0, null)->remove(0, false));
		$this->assertCollectionStoreEquals($collection, []);

		$this->assertEquals(null, $collection->remove(1));

		$this->assertEquals(false, $collection->remove(1, false));
	}

	public function testSearch()
	{
		$collection = new Collection(['key' => 'value']);

		$this->assertEquals('key', $collection->search('value'));

		$this->assertEquals(false, $collection->search('undefined'));

		$this->assertEquals(-1, $collection->search('undefined', -1));
	}

	public function testExists()
	{
		$collection = new Collection(['key' => 'value']);

		$this->assertTrue($collection->exists('key'));

		$this->assertFalse($collection->exists('undefined'));
	}

	public function testContains()
	{
		$collection = new Collection(['key' => 'value']);

		$this->assertTrue($collection->contains('value'));

		$this->assertFalse($collection->contains('undefined'));
	}

	public function testUpdate()
	{
		$collection = new Collection([[0]]);

		$this->assertInstanceOf(CollectionInterface::class, $collection->update([[1], [1]]));
		$this->assertCollectionStoreEquals($collection, [[0], [1]]);
	}

	public function testUpgrade()
	{
		$collection = new Collection([[0]]);

		$this->assertInstanceOf(CollectionInterface::class, $collection->upgrade([[1], [1]]));
		$this->assertCollectionStoreEquals($collection, [[1], [1]]);
	}

	public function testClear()
	{
		$collection = new Collection([0]);

		$this->assertInstanceOf(CollectionInterface::class, $collection->clear());
		$this->assertCollectionStoreEquals($collection, []);
	}

	public function testCount()
	{
		$collection = new Collection([0, 1, 2]);

		$this->assertEquals(3, $collection->count());
	}

	public function testAll()
	{
		$collection = new Collection([0]);

		$this->assertEquals([0], $collection->all());
	}

	public function testToArray()
	{
		$collection = new Collection([new Collection([0])]);

		$this->assertEquals([[0]], $collection->toArray());
	}

	public function testIsCountable()
	{
		$collection = new Collection([0, 1, 2]);

		$this->assertEquals(3, \count($collection));
	}

	public function testIsIterable()
	{
		$collection = new Collection();

		$this->assertTrue(\is_iterable($collection));
	}

	private function assertCollectionStoreEquals(CollectionInterface $collection, $expected)
	{
		$property = new \ReflectionProperty($collection, 'items');

		$property->setAccessible(true);

		$this->assertEquals($property->getValue($collection), $expected);
	}
}
