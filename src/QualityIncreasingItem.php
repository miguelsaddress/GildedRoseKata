<?php
require_once 'SpecificItem.php';

class QualityIncreasingItem extends SpecificItem {

	protected function calculateQualityDelta() {
		// "Aged Brie" actually increases in $quality the older it gets
		$delta = 1;
		return $delta;
	}
}
