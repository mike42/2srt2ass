<!DOCTYPE html>
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
        <a href="https://en.wikipedia.org/wiki/WebVTT">WebVTT</a> format. The output contains both tracks in <a href="">SubStation Alpha (SSA)</a> format.</p>

      <div class="subtitle-box preview-together" id="preview-box">
        <div class="subtitle-spacer-top"></div>
        <p class="subtitle subtitle-alt" id="preview-box-alt">Hello</p>
        <div class="subtitle-spacer-middle"></div>
        <p class="subtitle subtitle-main" id="preview-box-main">Bonjour</p>
      </div>

    </div>


    <form>

      <div class="row">
        <div class="col-lg-6">
          <div class="card">
            <div class="card-block">
              <h4 class="card-title">Main track</h4>
              <h6 class="card-subtitle mb-2 text-muted">This subtitle track will appear at the bottom of the display.</h6>

              <div class="form-group">
                <label for="exampleInputFile">Subtitle file</label>
                <input type="file" class="form-control-file" id="exampleInputFile" aria-describedby="fileHelp">
                <small id="fileHelp" class="form-text text-muted">File must be in WebVTT or SRT format.</small>
              </div>
              <div class="form-group">
                <label for="exampleInputFile">Display color</label>
                <div id="cp3" class="input-group colorpicker-component">
                  <input id="cp3-input" type="text" value="#FFFFFF" class="form-control" />
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
                <label for="exampleInputFile">Subtitle file</label>
                <input type="file" class="form-control-file" id="exampleInputFile" aria-describedby="fileHelp">
                <small id="fileHelp" class="form-text text-muted">File must be in WebVTT or SRT format.</small>
              </div>
              <div class="form-group">
                <label for="exampleInputFile">Display color</label>
                <div id="cp4" class="input-group colorpicker-component">
                  <input id="cp4-input" type="text" value="#FFFF00" class="form-control" />
                  <span class="input-group-addon"><i></i></span>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
      <h4>Output options</h4>
      <div class="form-group row">
        <label for="inputEmail3" class="col-sm-3 col-form-label">Display font</label>
        <div class="col-sm-9">
          <select class="form-control custom-select">
            <option selected>Open this select menu</option>
            <option value="1">One</option>
            <option value="2">Two</option>
            <option value="3">Three</option>
          </select>
        </div>
      </div>
      <div class="form-group row">
        <label for="inputPassword3" class="col-sm-3 col-form-label">Text size</label>
        <div class="col-sm-9">
          <select class="form-control custom-select">
            <option selected>Open this select menu</option>
            <option value="1">One</option>
            <option value="2">Two</option>
            <option value="3">Three</option>
          </select>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3">Vertical alignment</label>
        <div class="col-sm-9">
          <div class="custom-controls-stacked">
            <label class="custom-control custom-radio">
              <input name="radio-stacked" type="radio" id="alignTogetherBox" checked class="custom-control-input">
              <span class="custom-control-indicator"></span>
              <span class="custom-control-description">Both subtitles together at the bottom of the screen</span>
            </label>
            <label class="custom-control custom-radio">
              <input name="radio-stacked" type="radio" id="alignApartBox" class="custom-control-input">
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





  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <!-- <script src="js/site.min.js"></script> -->
  <script src="js/bootstrap-colorpicker.min.js"></script>
  <script>
    var colorSelectors = {
      'black': '#000000',
      'white': '#ffffff',
      'red': '#FF0000',
      'default': '#777777',
      'primary': '#337ab7',
      'success': '#5cb85c',
      'info': '#5bc0de',
      'warning': '#f0ad4e',
      'danger': '#d9534f'
    };
    $(function() {
      $('#cp3').colorpicker({
        format: 'hex',
        transparent: true,
        colorSelectors: colorSelectors
      }).on('changeColor', function(ev) {
        if(ev.value !== undefined) {
          $('@preview-box-main').css('color', ev.value);
        }
      });
      $('#cp4').colorpicker({
        format: 'hex',
        transparent: true,
        colorSelectors: colorSelectors
      }).on('changeColor', function(ev) {
        if(ev.value !== undefined) {
          $('#preview-box-alt').css('color', ev.value);
        }
      });

      $('#preview-box-main').css('color', $('#cp3-input').val())
      $('#preview-box-alt').css('color', $('#cp4-input').val());
      $('#alignTogetherBox').on('change', function() {
        $('#preview-box').addClass('preview-together');
      });
      $('#alignApartBox').on('change', function() {
        $('#preview-box').removeClass('preview-together');
      });

    });
  </script>
</body>

</html>
