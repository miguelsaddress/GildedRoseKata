<?php
require_once 'Item.php';

class SpecificItem extends Item {

	public function update() {
		$this->decreaseSellIn();
		$this->updateQuality();
	}

	protected function decreaseSellIn() {
		$this->sellIn -= 1;
	}

	protected function updateQuality() {
		$delta = $this->calculateQualityDelta();
		// Once the sell by date has passed, $quality
		// degrades twice as fast
		$delta = ($this->sellIn < 0) ? $delta*2 : $delta;
	    $this->quality += $delta;

	    $this->adjustQualityToFitLimits();
	}

	protected function calculateQualityDelta() {
	    $delta = -1;
		return $delta;
	}

	protected function adjustUpperLimit() {
		//The $quality of an item is never more than 50
		if ($this->quality >= 50) {
		    $this->quality = 50;
		}
	}

	protected function adjustLowerLimit() {
		// The $quality of an item is never negative
		if ($this->quality < 0) {
		    $this->quality = 0;
		}
	}

	private function adjustQualityToFitLimits() {
		$this->adjustUpperLimit();
		$this->adjustLowerLimit();
	}
}