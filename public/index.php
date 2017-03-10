<?php
require_once(__DIR__ . '/../vendor/autoload.php');

use CombineSubtitles\functions;

// Display page ?
if (!functions::try_get($_POST, 'send', $send) || $send != 'yes') {
    functions::sendHTML();
}

// set cookies
functions::try_get($_POST, 'fontname', $fontname, 'Arial');
functions::try_get($_POST, 'fontsize', $fontsize, '16');
functions::try_get($_POST, 'topColor', $topColor, '#FFFFF9');
functions::try_get($_POST, 'botColor', $botColor, '#F9FFF9');

functions::try_get($_POST, 'forceBottom', $forceBottom, '0');


functions::set_infinite_cookie('fontname', $fontname);
functions::set_infinite_cookie('fontsize', $fontsize);
functions::set_infinite_cookie('topColor', $topColor);
functions::set_infinite_cookie('botColor', $botColor);
functions::set_infinite_cookie('forceBottom', $forceBottom);

// Process
if (!functions:: try_get($_FILES, 'top', $top)
    || $top['error'] !== 0
    || !functions::try_get($_FILES, 'bot', $bot)
    || $bot['error'] !== 0
    ) {
        functions::sendHTML('Error while uploading. Try again.');
}

$outputName = preg_replace('/(\.[a-zA-Z]{2,3})?\.srt$/', '.ass', $bot['name']);
$contentTop = file_get_contents($top['tmp_name']);
$contentBot = file_get_contents($bot['tmp_name']);

functions::getStyles($fontname, $fontsize, $topColor, $botColor, $forceBottom, $styles, $stylesKeys);

functions::cleanSRT($contentTop);
functions::cleanSRT($contentBot);

$tree = array();

functions::parseAndAddSRT($contentTop, $tree, 'Top');
functions::parseAndAddSRT($contentBot, $tree, 'Bot');

usort($tree, array('CombineSubtitles\\functions', 'compare'));



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
    $outputData .= "Dialogue: 0,".functions::getTimeStamp($dialogue['start']).",".functions::getTimeStamp($dialogue['end']).",".$dialogue['type'].",,0000,0000,0000,,".$dialogue['text']."\r\n";
}

// Render
header("Content-type: application/octet-stream;");
header("Content-Disposition: attachment; filename=\"$outputName\"");
header("Expires: 0");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

echo $outputData;
