<?php
require_once 'GildedRose.php';
require_once 'Item.php';

class GildedRoseTest extends PHPUnit_Framework_TestCase {
    
    private $initialItems = [];
    const DAYS = 2;

    protected function setUp() {
        $this->initialItems = [
            new Item('+5 Dexterity Vest', 10, 20),
            new Item('Aged Brie', 2, 0),
            new Item('Elixir of the Mongoose', 5, 7),
            new Item('Sulfuras, Hand of Ragnaros', 0, 80),
            new Item('Sulfuras, Hand of Ragnaros', -1, 80),
            new Item('Backstage passes to a TAFKAL80ETC concert', 15, 20),
            new Item('Backstage passes to a TAFKAL80ETC concert', 10, 49),
            new Item('Backstage passes to a TAFKAL80ETC concert', 5, 49),
            // this conjured item does not work properly yet
            new Item('Conjured Mana Cake', 3, 6)
        ];
    }

    public function testHasPrivateItmsProperty() {
        $reflector = new ReflectionClass('GildedRose');
        $privateProperties = array_map(function ($e) {
            return $e->name;
        }, $reflector->getProperties(ReflectionProperty::IS_PRIVATE));

        $debugMessage = "items is not in the privates properties list => " 
                        . json_encode($privateProperties);
        $this->assertTrue(
            in_array("items", $privateProperties), 
            $debugMessage
        );     
    }

public function testExpectedOutput() {
    $app = new GildedRose($this->initialItems);
    $days = 2;
    for ($i = 0; $i < 2; $i++) {
        $app->updateQuality();
    }

    $items = $app->getItems();

    $expectedOutput = [
        "+5 Dexterity Vest, 8, 18",
        "Aged Brie, 0, 2",
        "Elixir of the Mongoose, 3, 5",
        "Sulfuras, Hand of Ragnaros, 0, 80",
        "Sulfuras, Hand of Ragnaros, -1, 80",
        "Backstage passes to a TAFKAL80ETC concert, 13, 22",
        "Backstage passes to a TAFKAL80ETC concert, 8, 50",
        "Backstage passes to a TAFKAL80ETC concert, 3, 50",
        "Conjured Mana Cake, 1, 4",
    ];

    for ($i=0; $i < count($items) ; $i++) {
        $itemStr = $items[$i]->__toString();
        $itemOutput = $expectedOutput[$i]; 
        $this->assertEquals($itemOutput, $itemStr);
    }

}





    // function testFoo() {
    //     $items = array(new Item("foo", 0, 0));
    //     $gildedRose = new GildedRose($items);
    //     $gildedRose->update_quality();
    //     $this->assertNotEquals("fixme", $items[0]->name);
    // }

}
