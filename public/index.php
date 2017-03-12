<?php
require_once(__DIR__ . '/../vendor/autoload.php');

use CombineSubtitles\Web;

// Process page
$converterWeb = new Web();
$error = null;
try {
    $formVals = $converterWeb -> processPage($_POST, $_FILES, $_COOKIE);
    if ($formVals === false) {
        // A file has already been sent to the user
        die();
    }
} catch (Exception $e) {
    $error = $e -> getMessage();
}
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>2srt2ssa</title>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
        <script type="text/javascript" src="jscolor/jscolor.js"></script>
        <link href="//fonts.googleapis.com/css?family=Noto+Sans" rel="stylesheet" type="text/css"></link>

<script type="text/javascript">
$(function() {
    $('option').each(function(index, Element) {
        $Element = $(Element);
        $Element.text($Element.attr('value'));
    });
});
</script>
<style type="text/css">
html { width: 100%; height: 100%; background: url("background.svg") no-repeat center center fixed; background-size: cover; }
body { font-family: 'Noto Sans', sans-serif; margin: 0px; padding: 0px; color: #595959; }
.page { width: 800px; margin: 25px auto; background-color: rgb(220, 220, 220); background-color: rgba(220, 220, 220, 0.75); }
h1 { background-color: #595959; color: #999999; padding: 3px 15px; }
h2 { background-color: #909090; color: #CCCCCC; padding: 3px 15px; }
h3 { padding: 3px 15px; }
p { padding: 0; margin: 20px 30px; }
.error { margin: 20px; border: 1px solid #909090; }
.error h2 { margin: 0; }
.error p { font-weight: bold; margin: 10px 15px; color: #451C1C; }

.donate { border: 1px solid grey; padding: 3px 12px; background-color: #EEEEEE; display: inline-block; border-radius: 15px; }
.donate a { color: red; text-decoration: none; }

.main-form { padding: 0 0 0 30px; }
.main-form form { }
.main-form form .form-row { clear: both; margin: 0 0 7px 0; }
.main-form form .form-row .form-label { width: 135px; float: left; }
.main-form form .form-row .form-input { }
.main-form form .form-row .form-input input { border: 1px solid #595959; }
.main-form form .form-row .form-input input[type=file] { width: 600px; }

</style>
    </head>
    <body>
        <div class="page">
            <h1 class="main-title">2srt2ssa</h1>
<?php if ($error != null) : ?>
            <div class="error">
                <h2>Error</h2>
                <p><?= $error ?></p>
            </div>
<?php endif; ?>
            <h3>Merge two .srt files, to one .ssa file</h3>
            <div class="main-form">
                <form action="." method="post" enctype="multipart/form-data" name="form">
                    <input type="hidden" name="send" value="yes">
                    
                    <div class="form-row">
                        <span class="form-label"><label for="top">Top subtitle</label> :</span>
                        <span class="form-input"><input type="file" id="top" name="top" /></span>
                    </div>
                    
                    <div class="form-row">
                        <span class="form-label"><label for="bot">Bottom subtitle</label> :</span>
                        <span class="form-input"><input type="file" id="bot" name="bot" /></span>
                    </div>
                    
                    <div class="form-row">
                        <span class="form-label"><label for="fontsize">Font size</label> :</span>
                        <span class="form-input">
                            <select id="fontsize" name="fontsize">
<?php
foreach (Web::FONT_SIZES as $fontSize) {
    $selected = $fontSize === $formVals['fontsize'] ? " selected" : "";
    echo "                            <option value=\"" . htmlspecialchars($fontSize) . "\"$selected></option>\n";
}
?>
                            </select>
                        </span>
                    </div>
                    
                    <div class="form-row">
                        <span class="form-label"><label for="fontname">Font name</label> :</span>
                        <span class="form-input">
                            <select id="fontname" name="fontname">
<?php
foreach (Web::FONT_NAMES as $fontName) {
    $selected = $fontName === $formVals['fontname'] ? " selected" : "";
    echo "                            <option value=\"" . htmlspecialchars($fontName) . "\"$selected></option>\n";
}
?>
                            </select>
                        </span>
                    </div>
                    
                    <div class="form-row">
                        <span class="form-label"><label for="topColor">Top color</label> :</span>
                        <span class="form-input"><input type="text" id="topColor" name="topColor" class="color {hash:true}" value="<?php echo htmlspecialchars($formVals['topColor']); ?>" />
                    </div>
                    
                    <div class="form-row">
                        <span class="form-label"><label for="botColor">Bottom color</label> :</span>
                        <span class="form-input"><input type="text" id="botColor" name="botColor" class="color {hash:true}" value="<?php echo htmlspecialchars($formVals['botColor']); ?>" />
                    </div>
                    
                    <div class="form-row">
                        <span class="form-label"><label for="forceBottom">Force bottom</label> :</span>
                        <span class="form-input"><input type="checkbox" id="forceBottom" name="forceBottom" value="1" /><label for="forceBottom"> [EXPERIMENTAL]</label></span>
                    </div>
                                        
                    <input type="submit" />
                    
                </form>
            </div>
            <div class="about">
                <h2>About</h2>
                <h3>How to use</h3>
                <p>Upload 2 srt files, the script will send you a SSA/ASS subtitle files. This file will have on top the fisrt file's subtitles and on bottom those of the second file.</p>
                <p>If you have any problem, make sure both your files are <a href="https://en.wikipedia.org/wiki/SubRip#SubRip_text_file_format">formatted in SubRip (srt)</a> and are using the same encoding (like UTF-8).</p>
                <h3>Author</h3>
                <p>2srt2ass developped by Vincent Rémond.</p>
                <p>Feedback or bugs at <a href="mailto:vincent.remond@gmail.com?subject=Feedback%202SRT2ASS">vincent.remond@gmail.com</a>.</p>
                <p>Program under GNU/GPL. <a href="https://github.com/vincentremond/2srt2ass/">Sources here</a></p>
                <p class="donate"><a href="http://www.actionagainsthunger.org/take-action/donate">DONATE</a></p>
                <h3>Changelog</h3>
                <ul>
                    <li><b>0.2.1</b> &ndash;  2017-01-22<br />- Added Helvetica &amp; minor change</li>
                    <li><b>0.2.0</b> &ndash;  2016-09-27<br />- Added force subtitles to bottom</li>
                    <li><b>0.1.1</b> &ndash;  2015-06-20<br />- Remove leading 0 in timestamps</li>
                    <li><b>0.1.0</b> &ndash;  2013-04-07<br />- Improved UI<br />- Customizable font output</li>
                    <li><b>0.0.2</b><br />- Fixed : Only Windows' style line returns for output file<br />- Added : Generated file's name based on bottom file<br />- Fixed : only display GIF files when downloading on Firefox</li>
                    <li><b>0.0.1</b><br />- Fixed : debug remains<br />- Added : removal of HTML and position tags</li>
                    <li><b>0.0.0</b><br />- First release</li>
                </ul>
            </div>
        </div>
<?php
$gaPath = $_SERVER["DOCUMENT_ROOT"] . '/include/google-analytics.php';
if (file_exists($gaPath)) {
    include($gaPath);
}
?>
</body>
</html>
