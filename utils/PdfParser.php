<?php

namespace Utils;

class PdfParser {
	public static function parseTextv1($path2file)
    {
        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile($path2file);

        $original_text = $pdf->getText();
        $text = nl2br($original_text); // Paragraphs and line break formatting
        $text = self::clean_ascii_characters($text); // Check special characters
        $text = str_replace(array("<br /> <br /> <br />", "<br> <br> <br>"), "<br /> <br />", $text); // Optional
        $text = addslashes($text); // Backslashes for single quotes     
        $text = stripslashes($text);
        $text = strip_tags($text);
         
        /**********************************************/
        /* Additional step to check formatting issues */
        // There may be some PDF formatting issues. I'm trying to check if the words are:
        // (a) Join. E.g., HelloWorld!Thereisnospacingbetweenwords
        // (b) splitted. E.g., H e l l o W o r l d ! E x c e s s i v e s p a c i n g
        $check_text = preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
         
        $no_spacing_error = 0;
        $excessive_spacing_error = 0;
        foreach($check_text as $word_key => $word) {
            if (strlen($word) >= 30) { // 30 is a limit that I set for a word length, assuming that no word would be 30 length long
                $no_spacing_error++;
            } else if (strlen($word) == 1) { // To check if the word is 1 word length
                if (preg_match('/^[A-Za-z]+$/', $word)) { // Only consider alphabetical words and ignore numbers.
                    $excessive_spacing_error++;
                }
            }
        }
         
        // Set the boundaries of errors you can accept
        // E.g., we reject the change if there are 30 or more $no_spacing_error or 150 or more $excessive_spacing_error issues
        // if ($no_spacing_error >= 30 || $excessive_spacing_error >= 150) {
        //     echo "Too many formatting issues<br />";
        //     echo $text;
        // } else {
        //     echo "Success!<br />";
        //     echo $text;
        // }
        /* End of additional step */
        /**************************/

        $text = preg_replace('/\s+/', ' ', $text);

        return $text;
    }

    public static function saveRawText($path2file)
    {
        $fd = popen(APP_PATH."/tools/xpdf-tools-linux-4.03/bin64/pdftotext '{$path2file}'", "r");
        pclose($fd);
        
        return explode(".", $path2file)[0].".txt";
    }

    public static function savePreprocessingText($path2file)
    {
        /*
         * Rule:
         * - exclude sentences with less than 5 word
         *
         */

        $read = File::readTXT($path2file);
        
        if(!$read['success'])
        {
            return false;
        }

        $textArr = preg_split('/\n+/', $read['content']);
        $newTextArr_a = array();

        foreach ($textArr as $value) 
        {
            if( count(explode(" ", $value)) >= 5 )
            {
                array_push($newTextArr_a, $value);
            }
        }
        
        $newTextArr_b = array();
        foreach ($newTextArr_a as $text) 
        {
            $tmpArr = Text::splitIntoSentences($text);
            foreach ($tmpArr as $item) 
            {
                if( count(explode(" ", $item)) >= 5 )
                {
                    array_push($newTextArr_b, $item);
                }
            }
        }

        // var_dump($newTextArr_b);

        $newFile2Path = explode(".", $path2file)[0]."-preprocessing.txt";
        $write = File::writeFromArray($newFile2Path, $newTextArr_b);

        if(!$write)
        {
            return false;
        }

        return $newFile2Path;
    }

    public static function getPreprocessingText($path2file)
    {
        $read = File::readTXT($path2file);
        
        if(!$read['success'])
        {
            return false;
        }

        $textArr = preg_split('/\n+/', $read['content']);
        $newTextArr_a = array();

        foreach ($textArr as $value) 
        {
            if( count(explode(" ", $value)) >= 5 )
            {
                array_push($newTextArr_a, $value);
            }
        }
        
        $newTextArr_b = array();
        foreach ($newTextArr_a as $text) 
        {
            $tmpArr = Text::splitIntoSentences($text);
            foreach ($tmpArr as $item) 
            {
                if( count(explode(" ", $item)) >= 5 )
                {
                    array_push($newTextArr_b, $item);
                }
            }
        }

        // var_dump($newTextArr_b);

        $preprocessing_text = "";

        foreach ($newTextArr_b as $key => $sentence) {
            if( $key != count($newTextArr_b)-1 )
            {
                $preprocessing_text .= $sentence."<SEP>";
            }else{
                $preprocessing_text .= $sentence;
            }
        }

        return $preprocessing_text;
    }

    private function clean_ascii_characters($string) 
    {
        $string = str_replace(array('-', '–'), '-', $string);
        $string = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $string);  
        return $string;
    }

    public static function genSentencePerLine($text)
    {
        $arr_sntcs = Text::splitIntoSentences($text);
        $new_sntcs = "";

        foreach ($arr_sntcs as $sentence) {
            $new_sntcs .= $sentence."\n";
        }

        return $new_sntcs;
    }

    public static function saveTXT($parsedText, $path2file)
    {
        $file = fopen($path2file, "w") or die("Unable to open file!");
        fwrite($file, $parsedText);
        fclose($file);
    }
}