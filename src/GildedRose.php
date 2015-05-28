<?php

class GildedRose {

    private $items;

    function __construct($items) {
        $this->items = $items;
    }

    public function getItems() {
        return $this->items;
    }

    function updateQuality() {
        foreach ($this->items as $item) {
            $item->decreaseSellIn();
            $item->updateQuality();
        }
    }
}
