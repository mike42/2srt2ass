<?php
namespace CombineSubtitles;

use CombineSubtitles\SsaOutputGenerator;

class CommandLine
{

    const ARGUMENTS = array(
        array(
            'short' => '',
            'long' => 'main:',
            'arg' => 'MAIN.srt',
            'help' => 'The main subtitle file. This will be displayed at the bottom of the screen.'
        ),
        array(
            'short' => '',
            'long' => 'alt:',
            'arg' => 'ALTERNATE.srt',
            'help' => 'The alternate subtitle file, in SRT format. This will be displayed at the top of the screen unless --together is set.'
        )
    );

    const OPTIONS = array(
        array(
            'short' => 'o:',
            'long' => '',
            'arg' => 'OUTPUT.ssa',
            'help' => 'Filename to write to. STDOUT is used if this is not set.'
        ),
        array(
            'short' => 'h',
            'long' => 'help',
            'help' => 'Show this information.'
        ),
        array(
            'short' => '',
            'long' => 'main-color:',
            'arg' => 'COLOR',
            'help' => 'Color of main subtitle as a hex code. #FFFFFF is used if this is not set.'
        ),
        array(
            'short' => '',
            'long' => 'alt-color:',
            'arg' => 'COLOR',
            'help' => 'Color of alternate subtitle as a hex code. #FFFFFF is used if this is not set.'
        ),
        array(
            'short' => '',
            'long' => 'font-name:',
            'arg' => 'FONT',
            'help' => 'Font name to use. \'Arial\' is used if this is not set.'
        ),
        array(
            'short' => '',
            'long' => 'font-size:',
            'arg' => 'SIZE',
            'help' => 'Font size to use. 16 is used if this is not set.'
        ),
        array(
            'short' => '',
            'long' => 'together',
            'help' => 'Render both subtitles together at the bottom of the screen'
        )
    );

    public function run(array $argv, $inp, $outp, $err)
    {
        // Parse command-line argumets
        $shortopts = "";
        $longopts = array();
        foreach (array_merge(self::ARGUMENTS, self::OPTIONS) as $option) {
            if ($option['short'] != '') {
                $shortopts .= $option['short'];
            }
            if ($option['long'] != '') {
                $longopts[] = $option['long'];
            }
        }
        $options = getopt($shortopts, $longopts);
        if (isset($options['help']) || isset($options['h'])) {
            // Return help if requested
            return $this->help($argv, $outp);
        }
        if (!isset($options['main']) || !isset($options['alt'])) {
            // Return usage if we don't have two filenames
            return $this->usage($argv, $err);
        }
        // Try to load values over defaults, just like the web
        $configured = array(
            'fontname' => isset($options['font-name'])? $options['font-name'] : SsaOutputGenerator::DEFAULT_FONT_NAME,
            'fontsize' => isset($options['font-size'])? $options['font-size'] : SsaOutputGenerator::DEFAULT_FONT_SIZE,
            'forceBottom' => isset($options['together']) ? '1' : SsaOutputGenerator::DEFAULT_FORCE_BOTTOM ? '1' : '0',
            'topColor' => isset($options['alt-color']) ? $options['alt-color'] : SsaOutputGenerator::DEFAULT_TOP_COLOR,
            'botColor' => isset($options['main-color']) ? $options['main-color'] : SsaOutputGenerator::DEFAULT_BOTTOM_COLOR
        );
        // Prepare I/O
        try {
            if (isset($options['o'])) {
                $outp = @fopen($options['o'], 'wb');
                if ($outp == false) {
                    throw new \Exception("Unable to open output file.");
                }
            }
            $subtitleTop = SrtInput::fromFile($options['alt']);
            $subtitleTop->setColor(new ColorWrapper($configured['topColor']));
            $subtitleBot = SrtInput::fromFile($options['main']);
            $subtitleBot->setColor(new ColorWrapper($configured['botColor']));
            // Go ahead and generate some output
            $outpGenerator = new SsaOutputGenerator();
            $outpGenerator->setFontName($configured['fontname']);
            $outpGenerator->setFontSize($configured['fontsize']);
            $outpGenerator->setForceBottom($configured['forceBottom'] == '1');
            $outputData = $outpGenerator->generate($subtitleTop, $subtitleBot);
            fwrite($outp, $outputData);
            fclose($outp);
        } catch (\Exception $e) {
            fwrite($err, "ERROR: " . $e -> getMessage() . "\n");
            exit(1);
        }
        return 0;
    }

    public function getShortOption(array $defined, $key)
    {
        foreach ($defined as $option) {
            if (trim($option['short'], ":") === $key) {
                return $option['short'];
            }
        }
        return false;
    }

    public function getLongOption(array $defined, $key)
    {
        foreach ($defined as $option) {
            if (trim($option['long'], ":") === $key) {
                return $option['long'];
            }
        }
        return false;
    }

    public function usage(array $argv, $dest)
    {
        fwrite($dest, "Usage: " . $argv[0] . " [OPTION]... --main MAIN.srt --alt ALTERNATE.srt\n");
        fwrite($dest, "Try '" . $argv[0] . "' --help for more information\n");
        return 2;
    }

    public function help(array $argv, $dest)
    {
        $helpStr = "Usage: " . $argv[0] . " [OPTION]... --main MAIN.srt --alt ALTERNATE.srt\n";
        $helpStr .= "Convert two SRT files into a single SSA file.\n\n";
        $helpStr .= $this->formatHelpSection(SELF::ARGUMENTS, "Mandatory arguments") . "\n";
        $helpStr .= $this->formatHelpSection(SELF::OPTIONS, "Options");
        fwrite($dest, $helpStr);
        return 0;
    }

    public function formatHelpSection(array $options, $title)
    {
        $helpStr = "$title:\n";
        foreach ($options as $option) {
            $versions = [];
            if ($option['long'] !== '') {
                $versions[] = "--" . trim($option['long'], ":") . (isset($option['arg']) ? ' ' . $option['arg'] : '');
            }
            
            if ($option['short'] !== '') {
                $versions[] = "-" . trim($option['short'], ":") . (isset($option['arg']) ? ' ' . $option['arg'] : '');
            }
            $helpStr .= $this->formatHelpLine(implode(", ", $versions), $option['help']);
        }
        return $helpStr;
    }

    public function formatHelpLine($opt, $description)
    {
        $optWidth = 20;
        $descWidth = 59;
        $descLines = explode("\n", wordwrap($description, 80, "\n", true));
        $optLines = explode("\n", wordwrap($opt, $optWidth, "\n", true));
        
        $i = 0;
        $outpLines = [];
        while (isset($descLines[$i]) || isset($optLines[$i])) {
            $left = isset($optLines[$i]) ? str_pad($optLines[$i], $optWidth) : str_repeat(" ", $optWidth);
            $right = isset($descLines[$i]) ? str_pad($descLines[$i], $descWidth) : str_repeat(" ", $descWidth);
            $outpLines[] = " " . $left . $right;
            $i ++;
        }
        return implode("\n", $outpLines) . "\n";
    }
}
