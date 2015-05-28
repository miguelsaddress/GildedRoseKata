<?php
require_once 'Item.php';

class SpecificItem extends Item {

	public function decreaseSellIn() {
		switch ($this->name) {
			case 'Sulfuras, Hand of Ragnaros':
			    break;

		    case 'Aged Brie':
		    case 'Backstage passes to a TAFKAL80ETC concert':
		    default:
		    	$this->sellIn -= 1;
		}
	}

	public function updateQuality() {
	    $delta = 0;
	    switch ($this->name) {
	    	case 'Sulfuras, Hand of Ragnaros':
	    	    // "Sulfuras", being a legendary item, never decreases in $quality
	    	    $delta = 0;
	    	    break;
	        case 'Aged Brie':
	            // "Aged Brie" actually increases in $quality the older it gets
	            $delta = 1;
	            // Once the sell by date has passed, $quality
	            // degrades twice as fast
	            $delta = ($this->sellIn < 0) ? $delta*2 : $delta;

	            break;

	        case 'Backstage passes to a TAFKAL80ETC concert':

	            if ($this->sellIn < 0) {
	                // $quality drops to 0 after the concert
	                $delta = $this->quality * (-1);
	            }
	            elseif ($this->sellIn <= 5) {
	            // $quality increases by 3 when there are 5 days or less
	                $delta = 3;
	            }
	            elseif ($this->sellIn <= 10) {
	                // $quality increases by 2 when there are 10 days or less
	                $delta = 2;
	            }
	            else {
	                // "Backstage passes", like aged brie, increases in $quality 
	                // as it's $sellIn value approaches;
	                $delta = 1;
	            }
	            
	            // Once the sell by date has passed, $quality
	            // degrades twice as fast
	            $delta = ($this->sellIn < 0) ? $delta*2 : $delta;

	            break;
	        default:
	            $delta = -1;
	            // Once the sell by date has passed, $quality
	            // degrades twice as fast
	            $delta = ($this->sellIn < 0) ? $delta*2 : $delta;

	            break;
	    }
	    $this->quality += $delta;
	    $this->adjustQualityToFitLimits();
	}

	private function adjustQualityToFitLimits() {
	    //The $quality of an item is never more than 50
	    if ($this->name != 'Sulfuras, Hand of Ragnaros' && $this->quality >= 50) {
	        $this->quality = 50;
	    }

	    // The $quality of an item is never negative
	    if ($this->quality < 0) {
	        $this->quality = 0;
	    }
	}
}