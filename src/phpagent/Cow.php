<?php
namespace phpagent;


class Cow {

    public static function say($text)
    {
        $message = self::getSpeechBubble($text);
        $cow = <<<DOC

$message
        \   ^__^
         \  (oo)\_______
            (__)\       )\/\
                ||----w |
                ||     ||

DOC;

        return $cow;
    }

    static public function getMessageLines($text)
    {
        $message = $text;
        $wrapLength = 40;
        // wrap the message to max chars
        $message = wordwrap($message, $wrapLength - 2);
        // split into array of lines
        return explode("\n", $message);
    }

    static public function getMaxLineLength(array $lines)
    {
        $lineLength = 0;
        // find the longest line
        foreach ($lines as $line) {
            $currentLineLength = strlen($line);
            if ($currentLineLength > $lineLength) {
                $lineLength = $currentLineLength;
            }
        }
        return $lineLength;
    }

    static public function getSpeechBubble($text)
    {
        $lines = self::getMessageLines($text);
        $lineLength = self::getMaxLineLength($lines);
        $text = '';
        $numberOfLines = count($lines);
        $firstLine = str_pad(array_shift($lines), $lineLength);
        if ($numberOfLines === 1) {
            $text = "< {$firstLine} >";
        } else {
            $lastLine = str_pad(array_pop($lines), $lineLength);
            $text = "/ {$firstLine} \\\n";
            foreach ($lines as $line) {
                $line = str_pad($line, $lineLength);
                $text .= "| {$line} |\n";
            }
            $text .= "\\ {$lastLine} /";
        }
        return $text;
    }
}