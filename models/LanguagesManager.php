<?php

class LanguagesManager extends Model {
    public function getLanguages ($speChar = false) {
        $lang = [] ;
        $file = fopen('languages.txt', 'r') ;
        while (!feof($file)) {
            $str = fgets($file) ;
            if ($str == '')
                continue ;
            if ($speChar == true)
                $str = htmlspecialchars($str) ;
            $lang[] = explode(':', $str) ;
        }
        return $lang ;
    }

    public function getLanguage ($sh) {
        $substr = ":$sh:" ;
        $lang = [] ;
        $file = fopen('languages.txt', 'r') ;
        while (!feof($file)) {
            $str = fgets($file) ;
            if ($str == '')
                continue ;
            if (strpos($str, $substr) != false) {
                $str = htmlspecialchars($str) ;
                $lang = explode(':', $str) ;
                if ($lang < 3) return [] ;
            }
        }
        return $lang ;
    }
}
