<?php
namespace CombineSubtitles;

class SsaOutputGenerator
{

    const DEFAULT_FONT_NAME = 'Arial';

    const DEFAULT_FONT_SIZE = 16;

    const DEFAULT_FORCE_BOTTOM = false;

    const DEFAULT_TOP_COLOR = '#FFFFF9';

    const DEFAULT_BOTTOM_COLOR = '#FFFFF9';

    protected $fontName;

    protected $fontSize;

    protected $forceBottom;

    public function __construct()
    {
        $this->fontName = self::DEFAULT_FONT_NAME;
        $this->fontSize = self::DEFAULT_FONT_SIZE;
    }

    public function setFontName($fontName)
    {
        $this->fontName = $fontName;
    }

    public function setFontSize($fontSize)
    {
        $this->fontSize = $fontSize;
    }

    public function setForceBottom($forceBottom)
    {
        $this->forceBottom = $forceBottom;
    }

    protected function getStyles(ColorWrapper $topColor, ColorWrapper $botColor, &$styles, &$keys)
    {
        $white = new ColorWrapper('#FFFFFF');
        $black = new ColorWrapper('#000000');
        $specifics = array(
            'Default' => array(
                'PrimaryColour' => $white->asRgbaHexLiteral(),
                "SecondaryColour" => $white->asRgbaHexLiteral(null, 1.2),
                'Alignment' => '2'
            ),
            'Top' => array(
                'PrimaryColour' => $topColor->asRgbaHexLiteral(),
                "SecondaryColour" => $topColor->asRgbaHexLiteral(null, 1.2),
                'Alignment' => ($this -> forceBottom ? '2' : '8')
            ),
            'Mid' => array(
                'PrimaryColour' => $white->asRgbaHexLiteral(),
                "SecondaryColour" => $white->asRgbaHexLiteral(null, 1.2),
                'Alignment' => '5'
            ),
            'Bot' => array(
                'PrimaryColour' => $botColor->asRgbaHexLiteral(),
                "SecondaryColour" => $botColor->asRgbaHexLiteral(null, 1.2),
                'Alignment' => '2'
            )
        );
        
        $defaults = array(
            'Fontname' => $this->fontName,
            'Fontsize' => $this->fontSize,
            'PrimaryColour' => $white->asRgbaHexLiteral(),
            "SecondaryColour" => $white->asRgbaHexLiteral(null, 1.2),
            'OutlineColour' => $black->asRgbaHexLiteral(),
            'BackColour' => $black->asRgbaHexLiteral(),
            'Bold' => '-1',
            'Italic' => '0',
            'Underline' => '0',
            'StrikeOut' => '0',
            'ScaleX' => '100',
            'ScaleY' => '100',
            'Spacing' => '0',
            'Angle' => '0',
            'BorderStyle' => '1',
            'Outline' => '3',
            'Shadow' => '0',
            'Alignment' => '2',
            'MarginL' => '10',
            'MarginR' => '10',
            'MarginV' => '10',
            'Encoding' => '0'
        );
        
        $keys = array_keys($defaults);
        
        $styles = array();
        foreach ($specifics as $key => $values) {
            $tmp = array();
            foreach ($defaults as $dk => $dv) {
                $tmp[$dk] = array_key_exists($dk, $values) ? $values[$dk] : $dv;
            }
            $styles[$key] = $tmp;
        }
    }

    public function generate(SrtInput $contentTop, SrtInput $contentBot)
    {
        $this->getStyles($contentTop -> getColor(), $contentBot -> getColor(), $styles, $stylesKeys);
        
        $tree = array();
        
        self::parseAndAddSRT($contentTop -> getCleanedContent(), $tree, 'Top');
        self::parseAndAddSRT($contentBot -> getCleanedContent(), $tree, 'Bot');
        
        usort($tree, array(
            'CombineSubtitles\\SsaOutputGenerator',
            'compare'
        ));
        
        // Everything ok, send file !
        
        $outputData = "";
        
        $outputData .= "[Script Info]\r\n";
        $outputData .= "ScriptType: v4.00+\r\n";
        $outputData .= "Collisions: Normal\r\n";
        $outputData .= "PlayDepth: 0\r\n";
        $outputData .= "Timer: 100,0000\r\n";
        $outputData .= "Video Aspect Ratio: 0\r\n";
        $outputData .= "WrapStyle: 0\r\n";
        $outputData .= "ScaledBorderAndShadow: no\r\n";
        $outputData .= "\r\n";
        $outputData .= "[V4+ Styles]\r\n";
        $outputData .= "Format: Name," . implode(',', $stylesKeys) . "\r\n";
        foreach ($styles as $styleName => $styleValues) {
            $outputData .= "Style: " . $styleName . "," . implode(',', $styleValues) . "\r\n";
        }
        $outputData .= "\r\n";
        $outputData .= "[Events]\r\n";
        $outputData .= "Format: Layer, Start, End, Style, Name, MarginL, MarginR, MarginV, Effect, Text\r\n";
        
        foreach ($tree as $dialogue) {
            $outputData .= "Dialogue: 0," . self::getTimeStamp($dialogue['start']) . "," . self::getTimeStamp($dialogue['end']) . "," . $dialogue['type'] . ",,0000,0000,0000,," . $dialogue['text'] . "\r\n";
        }
        return $outputData;
    }

    public static function parseAndAddSRT($text, &$tree, $type)
    {
        $eregEvent = '/\d+\n(\d\d:\d\d:\d\d)[\.,](\d\d)\d --> (\d\d:\d\d:\d\d)[\.,](\d\d)\d\n(.+?(\n.+?)*)\n\n/';
        preg_match_all($eregEvent, $text, $matches);
        
        if (count($matches[0]) === 0) {
            throw new \Exception('No subtitles found in SRT file for ' . strtolower($type) . '.');
        }
        
        for ($i = 0; $i < count($matches[0]); $i ++) {
            $tree[] = array(
                'start' => $matches[1][$i] . '.' . $matches[2][$i],
                'end' => $matches[3][$i] . '.' . $matches[4][$i],
                'type' => $type,
                'text' => str_replace("\n", '\N', $matches[5][$i])
            );
        }
        
        return null;
    }

    public static function getTimeStamp($str)
    {
        if ($str[0] == '0') {
            return substr($str, 1);
        }
        return $str;
    }

    public static function compare($a, $b)
    {
        if ($a['start'] < $b['start']) {
            return - 1;
        }
        if ($a['start'] > $b['start']) {
            return 1;
        }
        if ($a['type'] < $b['type']) {
            return - 1;
        }
        if ($a['type'] > $b['type']) {
            return 1;
        } else {
            return 0;
        }
    }
}
