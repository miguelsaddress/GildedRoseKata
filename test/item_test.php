<?php
require_once 'Item.php';

class ItemTest extends PHPUnit_Framework_TestCase {

    public function testHasInitialPublicProperties() {
        $properties = ["name", "sellIn", "quality"];
        $reflector = new ReflectionClass('Item');
        $publicProperties = array_map(function ($e) {
            return $e->name;
        }, $reflector->getProperties(ReflectionProperty::IS_PUBLIC));

        foreach ($properties as $prop) {
            $debugMessage = "$prop is not in the public properties list => " 
                            . json_encode($publicProperties);
            $this->assertTrue(
                in_array($prop, $publicProperties), 
                $debugMessage
            );     
        }
    }

    public function testToString() {
        $item = new Item("foo", 0, 0);
        $expected = "foo, 0, 0";
        $expectedFormat = "%s, %i, %i";
        $itemStr = $item->__toString();
        $this->assertStringMatchesFormat($expectedFormat, $itemStr);
        $this->assertEquals($itemStr, $expected);
    }


}
