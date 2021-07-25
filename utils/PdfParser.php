<?php

namespace Utils;

require_once 'vendor/autoload.php';

class PdfParser {
	public function parseText($filename)
    {
        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile("storage/".$filename);

        $original_text = $pdf->getText();
        $text = nl2br($original_text); // Paragraphs and line break formatting
        $text = $this->clean_ascii_characters($text); // Check special characters
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

        return $text;
    }

    private function clean_ascii_characters($string) {
        $string = str_replace(array('-', '–'), '-', $string);
        $string = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $string);  
        return $string;
    }
}