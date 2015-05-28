<?php
require_once 'GildedRose.php';
require_once 'SpecificItem.php';
require_once 'LegendaryItem.php';
require_once 'QualityIncreasingItem.php';
require_once 'ExponentialQualityIncreasingItem.php';


class GildedRoseTest extends PHPUnit_Framework_TestCase {
    
    private $initialItems = [];
    const DAYS = 2;

    protected function setUp() {
        $this->initialItems = [
            new SpecificItem('+5 Dexterity Vest', 10, 20),
            new QualityIncreasingItem('Aged Brie', 2, 0),
            new SpecificItem('Elixir of the Mongoose', 5, 7),
            new LegendaryItem('Sulfuras, Hand of Ragnaros', 0),
            new LegendaryItem('Sulfuras, Hand of Ragnaros', -1),
            new ExponentialQualityIncreasingItem('Backstage passes to a TAFKAL80ETC concert', 15, 20),
            new ExponentialQualityIncreasingItem('Backstage passes to a TAFKAL80ETC concert', 10, 49),
            new ExponentialQualityIncreasingItem('Backstage passes to a TAFKAL80ETC concert', 5, 49),
            // this conjured item does not work properly yet
            new SpecificItem('Conjured Mana Cake', 3, 6)
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

    public function testQualityDecreasesTwiceAsFastWhenExpired() {
        //Once the sell by date has passed, $quality degrades twice as fast
        $item = new SpecificItem('Elixir of the Mongoose', 0, 7);
        $app = new GildedRose([$item]);
        $app->updateQuality();

        $this->assertEquals($item->quality, 5);
    }

    public function testQualityOfItemIsNeverNegative() {
        // The $quality of an item is never negative
        $item = new SpecificItem('Elixir of the Mongoose', 0, 0);
        $app = new GildedRose([$item]);
        $app->updateQuality();

        $this->assertEquals($item->quality, 0);

        //more days pass and it is still zero
        $app->updateQuality();
        $app->updateQuality();
        $app->updateQuality();

        $this->assertEquals($item->quality, 0);
    }

    public function testAgedBrieIncreasesQualityTheOlderItGets() {
        // "Aged Brie" actually increases in $quality the older it gets
        $item = new QualityIncreasingItem('Aged Brie', 2, 0);
        $app = new GildedRose([$item]);
        $app->updateQuality();
        $this->assertEquals($item->quality, 1);
        $app->updateQuality();
        $this->assertEquals($item->quality, 2);
    }

    public function testQualityIsNeverMoreThan50() {
        // The $quality of an item is never more than 50
        $item = new QualityIncreasingItem('Aged Brie', 2, 49);
        $app = new GildedRose([$item]);
        $app->updateQuality();
        $this->assertEquals($item->quality, 50);
        $app->updateQuality();
        $this->assertEquals($item->quality, 50);
        $app->updateQuality();
        $this->assertEquals($item->quality, 50);
    }

    public function testBackstagePassesIncreasesQualityTheOlderItGets() {
        //"Backstage passes" increases in $quality as it's $sellIn value approaches;
        //When sellIn is greater than 10

        $item = new ExponentialQualityIncreasingItem('Backstage passes to a TAFKAL80ETC concert', 22, 0);
        $app = new GildedRose([$item]);
        $app->updateQuality();
        $this->assertEquals($item->quality, 1);
        $app->updateQuality();
        $this->assertEquals($item->quality, 2);
    }

    public function testBackStageSellInIn10DaysOrLess() {
        // "Backstage passes" increases in $quality as it's $sellIn value approaches;
        // $quality increases by 2 when there are 10 days or less 
        $item = new ExponentialQualityIncreasingItem('Backstage passes to a TAFKAL80ETC concert', 10, 0);
        $app = new GildedRose([$item]);
        $app->updateQuality();
        $this->assertEquals($item->quality, 2);
        $app->updateQuality();
        $this->assertEquals($item->quality, 4);
    }

    public function testBackStageSellInIn5DaysOrLess() {
        // "Backstage passes" increases in $quality as it's $sellIn value approaches;
        // and by 3 when there are 5 days or less
        $item = new ExponentialQualityIncreasingItem('Backstage passes to a TAFKAL80ETC concert', 5, 0);
        $app = new GildedRose([$item]);
        $app->updateQuality();
        $this->assertEquals($item->quality, 3);
        $app->updateQuality();
        $this->assertEquals($item->quality, 6);
    }


    public function testBackStageQualityDropsToZeroWhenExpired() {
        // "Backstage passes" increases in $quality as it's $sellIn value approaches;
        // but $quality drops to 0 after the concert
        $item = new ExponentialQualityIncreasingItem('Backstage passes to a TAFKAL80ETC concert', 1, 0);
        $app = new GildedRose([$item]);
        //Didnt expire, so the quality increases
        $app->updateQuality();
        $this->assertEquals($item->quality, 3);
        //Now it expires, so the quality goes to Zero
        $app->updateQuality();
        $this->assertEquals($item->quality, 0);
    }


}
