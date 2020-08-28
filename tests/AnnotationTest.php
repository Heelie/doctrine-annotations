<?php


namespace EasySwoole\DoctrineAnnotation\Tests;


use EasySwoole\DoctrineAnnotation\AnnotationReader;
use PHPUnit\Framework\TestCase;
use EasySwoole\DoctrineAnnotation\Tests\Tag\PropertyTag;
use EasySwoole\DoctrineAnnotation\Tests\Tag\NonePropertyTag;


class AnnotationTest extends TestCase
{
    /**
     * @PropertyTag(input={"code":2})
     * @PropertyTag(input=r"{"code":2,"result":[{"name":1}]}")
     */
    private $a;

    /**
     * @NonePropertyTag({"code":2})
     * @NonePropertyTag(r"{"code":2,"result":[{"name":1}]}")
     */
    private $b;

    /**
     * @PropertyTag(input={"code":2,"result":[{"name":1}]})
     */
    private $jsonArray;

    private $ref;
    private $reader;

    function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->ref = new \ReflectionClass(static::class);
        $this->reader = new AnnotationReader();
        parent::__construct($name, $data, $dataName);
    }

    function testProperty()
    {
        $ret = $this->reader->getPropertyAnnotations($this->ref->getProperty('a'));
        $this->assertIsArray($ret);
        $this->assertEquals(2,count($ret));
        $this->assertEquals(["code"=>2],$ret[0]->input);
        $this->assertEquals('{"code":2,"result":[{"name":1}]}',$ret[1]->input);
    }

    function testNoneProperty()
    {
        $ret = $this->reader->getPropertyAnnotations($this->ref->getProperty('b'));
        $this->assertIsArray($ret);
        $this->assertEquals(2,count($ret));
        $this->assertEquals(["code"=>2],$ret[0]->value);
        $this->assertEquals('{"code":2,"result":[{"name":1}]}',$ret[1]->value);
    }

    function testJsonArray()
    {
        $ret = $this->reader->getPropertyAnnotations($this->ref->getProperty('jsonArray'));
        $this->assertEquals(['code'=>2,'result'=>[['name'=>1]]],$ret[0]->input);
    }
}