<?php

class LanguagesManager extends Model {
    public function getLanguages($speChar = false) {
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
}
