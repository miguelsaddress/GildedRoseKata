<?php
require_once 'SpecificItem.php';

class ExponentialQualityIncreasingItem extends SpecificItem {
	protected function calculateQualityDelta() {
		$delta = 0;
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
		return $delta;
	}
}