<?php
require_once(__DIR__ . '/../vendor/autoload.php');

use CombineSubtitles\Web;

// Process page
$converterWeb = new Web();
$error = null;
$formVals = $converterWeb -> processPage($_POST, $_FILES, $_COOKIE);
if ($formVals === false) {
    // A file has already been sent to the user
    die();
}
if (isset($formVals['error'])) {
    $error = $formVals['error'];
}
if (php_sapi_name() === "cli") {
    // Ensure we get the error box on the CLI.
    $error = "Console in use";
}
?><!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  <title>Subtitle merge tool - 2srt2ssa</title>

  <!-- Bootstrap -->
  <link href="css/site.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Lato|Open+Sans" rel="stylesheet">
  <link rel="icon" type="image/x-icon" href="images/favicon32.ico" />
  <link rel="icon" type="image/png" href="images/favicon32.png" />

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>

<body>
  <div class="container">
    <div class="header clearfix">
      <ul class="nav nav-pills float-right">
        <li class="nav-item">
          <a class="nav-link active" href="#">Web <span class="sr-only">(current)</span></a>
        </li>
        <!-- <li class="nav-item">
          <a class="nav-link" href="#cli">Command-line</a>
        </li> -->
        <li class="nav-item">
          <a class="nav-link" href="https://github.com/mike42/2srt2ssa">Code</a>
        </li>
      </ul>
      <h2>Subtitle merge tool</h2>
    </div>

    <div class="jumbotron">

      <p class="lead">This online tool allows you to merge two subtitle tracks.</p>

      <div class="row text-center">
        <div class="col-sm-3 offset-sm-2">
          <div class="subtitle-box preview-together">
            <div class="subtitle-spacer-top"></div>
            <p class="subtitle subtitle-alt"></p>
            <p class="subtitle subtitle-main">Hello</p>
          </div>
        </div>
        <div class="col-sm-2">
          <p class="big-char">+</p>
        </div>
        <div class="col-sm-3">
          <div class="subtitle-box preview-together">
            <div class="subtitle-spacer-top"></div>
            <p class="subtitle subtitle-alt"></p>
            <p class="subtitle subtitle-main">Bonjour</p>
          </div>
        </div>
      </div>

      <p class="lead">You will need your input tracks to be in <a href="https://en.wikipedia.org/wiki/SubRip">SubRip (SRT)</a> or
        <a href="https://en.wikipedia.org/wiki/WebVTT">WebVTT</a> format. The output contains both tracks in <a href="">SubStation Alpha (SSA)</a> format.
      </p>

      <div class="subtitle-box preview-together" id="preview-box">
        <div class="subtitle-spacer-top"></div>
        <p class="subtitle subtitle-alt" id="preview-box-alt">Hello</p>
        <div class="subtitle-spacer-middle"></div>
        <p class="subtitle subtitle-main" id="preview-box-main">Bonjour</p>
      </div>

    </div>

    <form action="." method="post" enctype="multipart/form-data" name="form">
        <input type="hidden" name="send" value="yes">
<?php if ($error !== null) : ?>
      <div class="alert alert-danger" role="alert">
        <strong>Error!</strong> <?= htmlentities($error) ?>
      </div>
<?php endif; ?>
      <div class="row">
        <div class="col-lg-6">
          <div class="card">
            <div class="card-block">
              <h4 class="card-title">Main track</h4>
              <h6 class="card-subtitle mb-2 text-muted">This subtitle track will appear at the bottom of the display.</h6>

              <div class="form-group">
                <label for="mainInputFile">Subtitle file</label>
                <input type="file" class="form-control-file" id="mainInputFile" aria-describedby="mainFileHelp" name="bot">
                <small id="mainFileHelp" class="form-text text-muted">File must be in WebVTT or SRT format.</small>
              </div>
              <div class="form-group">
                <label for="mainInputFile">Display color</label>
                <div id="main-color" class="input-group colorpicker-component">
                  <input id="main-color-input" type="text" value="<?php echo htmlspecialchars($formVals['botColor']); ?>" name="botColor" class="form-control" />
                  <span class="input-group-addon"><i></i></span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="card">
            <div class="card-block">
              <h4 class="card-title">Alternative track</h4>
              <h6 class="card-subtitle mb-2 text-muted">This subtitle track will be displayed above the main track.</h6>
              <div class="form-group">
                <label for="altInputFile">Subtitle file</label>
                <input type="file" class="form-control-file" id="altInputFile" aria-describedby="altFileHelp" name="top">
                <small id="altFileHelp" class="form-text text-muted">File must be in WebVTT or SRT format.</small>
              </div>
              <div class="form-group">
                <label for="altInputFile">Display color</label>
                <div id="alt-color" class="input-group colorpicker-component">
                  <input id="alt-color-input" type="text" value="<?php echo htmlspecialchars($formVals['topColor']); ?>" name="topColor" class="form-control" />
                  <span class="input-group-addon"><i></i></span>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
      <h4>Output options</h4>
      <div class="form-group row">
        <label for="fontName" class="col-sm-3 col-form-label">Display font</label>
        <div class="col-sm-9">
          <select id="fontName" class="form-control custom-select" name="fontname">
<?php
foreach (Web::FONT_NAMES as $fontName) {
    $selected = $fontName === $formVals['fontname'] ? " selected" : "";
    echo "            <option value=\"" . htmlspecialchars($fontName) . "\"$selected>". htmlspecialchars($fontName) . "</option>\n";
}
?>
          </select>
        </div>
      </div>
      <div class="form-group row">
        <label for="fontSize" class="col-sm-3 col-form-label">Text size</label>
        <div class="col-sm-9">
          <select id="fontSize" class="form-control custom-select" name="fontsize">
<?php
foreach (Web::FONT_SIZES as $fontSize) {
    $selected = $fontSize === $formVals['fontsize'] ? " selected" : "";
    echo "            <option value=\"" . htmlspecialchars($fontSize) . "\"$selected>" . htmlspecialchars($fontSize) . "</option>\n";
}
?>
          </select>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3">Vertical alignment</label>
        <div class="col-sm-9">
          <div class="custom-controls-stacked">
            <label class="custom-control custom-radio">
              <input name="forceBottom" value="1" type="radio" id="alignTogetherBox" checked class="custom-control-input">
              <span class="custom-control-indicator"></span>
              <span class="custom-control-description">Both subtitles together at the bottom of the screen</span>
            </label>
            <label class="custom-control custom-radio">
              <input name="forceBottom" value="0" type="radio" id="alignApartBox" class="custom-control-input">
              <span class="custom-control-indicator"></span>
              <span class="custom-control-description">Main track at the bottom, alternate at the top,</span>
            </label>
          </div>
        </div>
      </div>
      <div class="form-group row">
        <div class="offset-sm-3 col-sm-9">
          <button type="submit" class="btn btn-primary">Merge these files</button>
        </div>
      </div>
    </form>

    <footer class="footer">
      <p><a href="https://github.com/mike42/2srt2ssa">2srt2ssa</a> is distributed under the terms of the GNU General Public License, version 3.</p>
      <p>Your use of this website is entirely at your own risk. No warranty is provided by any party, implied or otherwise.</p>
    </footer>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="js/site.min.js"></script>
</body>
</html>
