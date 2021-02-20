<?php

class SidebarMenuItem {

    private $_name;
    private $_icon;
    private $_color;
    private $_link;

    public function __construct($name, $link = '#', $icon = null, $color = '#81B29A') {
        if( isset($name, $link, $icon, $color) ){
            $this->hydrate([
                'name' => $name,
                'link' => $link,
                'icon' => $icon,
                'color' => $color
                ]);
        }
    }

    public function hydrate(array $data) {
        foreach ($data as $k => $v) {
            $method = 'set' . ucfirst($k);
            if( method_exists($this, $method) )
                $this->$method($v);
        }
    }

    // SETTERS
    public function setName($name) {
        if( is_string($name) && strlen($name) > 0 )
            $this->_name = $name;
    }

    public function setLink($link) {
        if( is_string($link) && $link !== '' )
            $this->_link = $link === '#' ? '#' : WEB_ROOT . $link;

    }

    public function setIcon($icon) {
        if( is_string($icon) && strlen($icon) > 0 ){
            ob_start();
            if( file_exists("media/svg/$icon.svg") )
                include "media/svg/$icon.svg";
            $svg = ob_get_clean();
            if( $svg !== '' )
                $this->_icon = $svg;
        }
    }

    public function setColor($color) {
        if( is_string($color) && $color !== '' )
            $this->_color = $color;
    }

    // GETTERS
    public function name() {
        return $this->_name;
    }

    public function icon() {
        return $this->_icon;
    }

    public function Color() {
        return $this->_color;
    }

    public function Link() {
        return $this->_link;
    }

}