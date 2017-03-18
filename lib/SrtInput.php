<?php
namespace CombineSubtitles;

class SrtInput
{
    protected $subtitleText;
    protected $color;
 
    protected function __construct($subtitleText)
    {
        $this -> subtitleText = $subtitleText;
        $this -> color = null;
        self::cleanSrt($subtitleText);
    }

    public static function cleanSrt(&$text)
    {
        $text = strip_tags($text);
    
        $text .= "\n";
    
        $patterns = array();
        $replacements = array();
        // Windows to Unix
        $patterns[] = '/\r\n/';
        $replacements[] = "\n";
        // Too much spaces
        $patterns[] = '/[ \t]+\n/';
        $replacements[] = "\n";
        // Too much spaces
        $patterns[] = '/\n\n\n+/';
        $replacements[] = "\n\n";
        // Tags
        $patterns[] = '/\{\\\\pos\(\d+,\d+\)\}/';
        $replacements[] = "";
    
        $text = preg_replace($patterns, $replacements, $text);
    
        return null;
    }
    
    public function setColor(ColorWrapper $color)
    {
        $this -> color = $color;
    }
    
    public function getColor()
    {
        return $this -> color;
    }
    
    public function getCleanedContent()
    {
        return $this -> subtitleText;
    }

    public static function fromFile($fileName)
    {
        $subtitleText = @file_get_contents($fileName);
        if ($subtitleText === false) {
            throw new \Exception("Unable to open input file '$fileName'");
        }
        return new SrtInput($subtitleText);
    }
}
