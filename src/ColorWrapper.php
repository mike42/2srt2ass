<?php

namespace CombineSubtitles;

class ColorWrapper
{
    public function __construct($rgbHex)
    {
        $this -> rgbHex = $rgbHex;
    }

    public function asRgbaHexLiteral($alpha = null, $change = null)
    {
        $color = $this -> rgbHex;
        if ($alpha === null) {
            $alpha = 0;
        }
        if ($change === null) {
            $change = 1.0;
        }
        if (! preg_match('/^#?([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})$/i', $color, $parts)) {
            return "&H00FFFFFF";
        }
        $out = '&H';
        $values = array(
            $alpha
        );
        for ($i = 3; $i >= 1; $i --) {
            $value = hexdec($parts[$i]);
            $value = intval(min(255, round($value * $change))); // 80/100 = 80%, i.e. 20% darker
            $values[] = $value;
        }
        for ($i = 0; $i < count($values); $i ++) {
            $out .= str_pad(dechex($values[$i]), 2, '0', STR_PAD_LEFT);
        }
        return strtoupper($out);
    }
}
