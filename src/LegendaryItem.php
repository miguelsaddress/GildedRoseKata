<?php
require_once 'SpecificItem.php';

class LegendaryItem extends SpecificItem {
	public $quality = 80;

	public function decreaseSellIn() {
		// a legendary item, never has to be sold
	}

	protected function calculateQualityDelta() {
		// a legendary item, never decreases in $quality
		return 0;
	}

	protected function adjustLowerLimit() {
		// legendary item and as such its $quality is 80 and it never alters.
		$this->quality = 80;
	}

	protected function adjustUpperLimit() {
		// legendary item and as such its $quality is 80 and it never alters.
		$this->quality = 80;
	}

}