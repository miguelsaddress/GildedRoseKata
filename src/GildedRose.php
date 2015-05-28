<?php
require_once 'Item.php';

class GildedRose {

    private $items;

    function __construct($items) {
        $this->items = $items;
    }

    public function getItems() {
        return $this->items;
    }

    private function getQualityDelta(Item $item) {
        $delta = 0;
        switch ($item->name) {
            case 'Aged Brie':
                // The $quality of an item is never negative
                if ($item->quality < 0) {
                    $delta = 0;
                }
                //The $quality of an item is never more than 50
                else if ($item->quality < 50) {
                    $delta = 1;
                } else {
                    // "Aged Brie" actually increases in $quality the older it gets
                    $delta = 1;
                }
                // Once the sell by date has passed, $quality
                // degrades twice as fast
                $delta = ($item->sellIn < 0) ? $delta*2 : $delta;

                break;

            case 'Backstage passes to a TAFKAL80ETC concert':

                // The $quality of an item is never negative
                if ($item->quality < 0) {
                    $delta = 0;
                }
                //The $quality of an item is never more than 50
                else if ($item->quality < 50) {
                    $delta = 1;
                } else {
                    if ($item->sellIn < 0) {
                        // $quality drops to 0 after the concert
                        $delta = $item->quality;
                    }
                    elseif ($item->sellIn <= 5) {
                    // $quality increases by 3 when there are 5 days or less
                        $delta = 3;
                    }
                    elseif ($item->sellIn <= 10) {
                        // $quality increases by 2 when there are 10 days or less
                        $delta = 2;
                    }
                    else {
                        // "Backstage passes", like aged brie, increases in $quality 
                        // as it's $sellIn value approaches;
                        $delta = 1;
                    }
                }
                // Once the sell by date has passed, $quality
                // degrades twice as fast
                $delta = ($item->sellIn < 0) ? $delta*2 : $delta;

                break;
            case 'Sulfuras, Hand of Ragnaros':
                // "Sulfuras", being a legendary item, never decreases in $quality
                $delta = 0;
                break;
            default:
                $delta = -1;
                // Once the sell by date has passed, $quality
                // degrades twice as fast
                $delta = ($item->sellIn < 0) ? $delta*2 : $delta;

                break;
        }

        return $delta;
    }

    function updateQuality() {
        foreach ($this->items as $item) {
            $qualityDelta = $this->getQualityDelta($item);

            if ($item->name != 'Aged Brie' and $item->name != 'Backstage passes to a TAFKAL80ETC concert') {
                if ($item->quality > 0) {
                    if ($item->name != 'Sulfuras, Hand of Ragnaros') {
                        $item->quality = $item->quality + $qualityDelta;
                    }
                }
            } else {
                if ($item->quality < 50) {
                    $item->quality = $item->quality + $qualityDelta;
                    if ($item->name == 'Backstage passes to a TAFKAL80ETC concert') {
                        if ($item->sellIn < 11) {
                            if ($item->quality < 50) {
                                $item->quality = $item->quality + $qualityDelta;
                            }
                        }
                        if ($item->sellIn < 6) {
                            if ($item->quality < 50) {
                                $item->quality = $item->quality + $qualityDelta;
                            }
                        }
                    }
                }
            }
            
            if ($item->name != 'Sulfuras, Hand of Ragnaros') {
                $item->sellIn = $item->sellIn - 1;
            }

            if ($item->sellIn < 0) {
                if ($item->name != 'Aged Brie') {
                    if ($item->name != 'Backstage passes to a TAFKAL80ETC concert') {
                        if ($item->quality > 0) {
                            if ($item->name != 'Sulfuras, Hand of Ragnaros') {
                                $item->quality = $item->quality + $qualityDelta;
                            }
                        }
                    } else {
                        $item->quality = $item->quality - $item->quality;
                    }
                } else {
                    if ($item->quality < 50) {
                        $item->quality = $item->quality + $qualityDelta;
                    }
                }
            }
        }
    }
}
