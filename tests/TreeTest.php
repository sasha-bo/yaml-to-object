<?php

namespace Unicon\tests;

use PHPUnit\Framework\TestCase;
use Unicon\Yaml\Examples\Tree;
use Unicon\Yaml\Yaml;

include_once(__DIR__.'/examples/Tree.php');

final class TreeTest extends TestCase
{
    private Yaml $yaml;
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->yaml = new Yaml(Tree::class);
    }

    public function testSimpleSuccess1(): void
    {
        $object = $this->yaml->read(__DIR__.'/examples/tree-success-1.yml');
        $this->assertInstanceOf(Tree::class, $object);
        $this->assertSame(777, $object->integerParameter);
        $this->assertSame(1, $object->positiveIntegerParameter);
        $this->assertSame(null, $object->booleanOrNullParameter);
        $this->assertSame('888', $object->stringParameter);
        $this->assertCount(2, $object->children);
        $this->assertSame([0, 1], array_keys($object->children));
        $this->assertInstanceOf(Tree::class, $object->children[0]);
        $this->assertSame(888, $object->children[0]->integerParameter);
        $this->assertSame(2, $object->children[0]->positiveIntegerParameter);
        $this->assertSame(true, $object->children[0]->booleanOrNullParameter);
        $this->assertSame('999', $object->children[0]->stringParameter);
        $this->assertCount(5, $object->children[0]->children);
        $this->assertSame(991, $object->children[0]->children[0]->positiveIntegerParameter);
        $this->assertSame(992, $object->children[0]->children[1]->positiveIntegerParameter);
        $this->assertSame(993, $object->children[0]->children[2]->positiveIntegerParameter);
        $this->assertSame(994, $object->children[0]->children[3]->positiveIntegerParameter);
        $this->assertSame(995, $object->children[0]->children[4]->positiveIntegerParameter);
    }
}
