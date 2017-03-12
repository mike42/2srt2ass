<?php
namespace CombineSubtitles;

class Web
{
    public function getFile(array $files, $name)
    {
        if (!isset($files[$name]) || $files[$name]['error'] !== 0) {
            throw new Exception('File was not uploaded. Please try again.');
        }
        $file = $files[$name];
        return $file;
    }
    
    public function fillInDefaults(array $defaults, array $configured)
    {
        $ret = array();
        foreach ($defaults as $key => $val) {
            $ret[$key] = isset($configured[$key]) ? $configured[$key] : $val;
        }
        return $ret;
    }

    public function saveValuesAsCookies(array $vals)
    {
        if (php_sapi_name() === 'cli') {
            return false;
        }
        $expiry = time() + (20 * 365 * 24 * 60 * 60);
        foreach ($vals as $key => $val) {
            setcookie($key, $val, $expiry);
        }
    }

    public function processPage(array $post, array $files, array $cookie)
    {
        // Store as array, so we can iterates
        $defaults = array(
            'fontname' => SsaOutputGenerator::DEFAULT_FONT_NAME,
            'fontsize' => SsaOutputGenerator::DEFAULT_FONT_SIZE,
            'forceBottom' => SsaOutputGenerator::DEFAULT_FORCE_BOTTOM ? '1' : '0',
            'topColor' => SsaOutputGenerator::DEFAULT_TOP_COLOR,
            'botColor' => SsaOutputGenerator::DEFAULT_BOTTOM_COLOR
        );

        // Display page if not posting
        if (!isset($post['send'])) {
            // Return values set via cookies, otherwise use defaults
            return $this -> fillInDefaults($defaults, $cookie);
        }

        // Retrieve values if set via post, otherwise use defaults
        $configured = $this -> fillInDefaults($defaults, $post);
        $this -> saveValuesAsCookies($configured);
        // Process uploaded files
        $top = $this -> getFile($files, 'top');
        $bot = $this -> getFile($files, 'bot');

        // Get output name
        $outputName = preg_replace('/(\.[a-zA-Z]{2,3})?\.srt$/', '.ass', $bot['name']);

        $subtitleTop = SrtInput::fromFile($top['tmp_name']);
        $subtitleTop->setColor(new ColorWrapper($configured['topColor']));

        $subtitleBot = SrtInput::fromFile($bot['tmp_name']);
        $subtitleBot->setColor(new ColorWrapper($configured['botColor']));

        $outpGenerator = new SsaOutputGenerator();
        $outpGenerator->setFontName($configured['fontname']);
        $outpGenerator->setFontSize($configured['fontsize']);
        $outpGenerator->setForceBottom($configured['forceBottom'] == '1');
        $outputData = $outpGenerator->generate($subtitleTop, $subtitleBot);

        // Render
        header("Content-type: application/octet-stream;");
        header("Content-Disposition: attachment; filename=\"$outputName\"");
        header("Expires: 0");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        echo $outputData;
        return false;
    }
}
