<?php
require_once 'SpecificItem.php';

class QuickDegradingItem extends SpecificItem {

    protected function calculateQualityDelta() {
        // This items degrade in $quality twice as fast as normal items
        $delta = -2;
        return $delta;
    }
}
