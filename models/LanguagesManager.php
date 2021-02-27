<?php

class LanguagesManager extends Model {
    public function getLanguages ($speChar = false) {
        $lang = json_decode(file_get_contents('languages.json'), true);
        return $lang ;
    }
}
