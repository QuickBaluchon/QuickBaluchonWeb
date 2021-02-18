<?php

class SidebarItem extends Sidebar {

    private $_name;
    private $_icon;
    private $_iconBackgroungColor;
    private $_link;

    public function __construct($name, $link, $icon, $color) {
        if( isset($name, $link, $icon, $color) ){
            $this->hydrate([$name, $link, $icon, $color]);
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
            $this->_link = WEB_ROOT . '/' . $this->_role .'/'. $link;

    }

    public function setIcon($icon) {
        if( is_string($link) && strlen($link) > 0 ){
            ob_start();
            include "media/svg/$icon.svg";
            $svg = ob_get_clean();
            if( $svg !== '' )
                $this->_icon = $svg;
        }
    }

    public function setIconBackgroungColor($icon) {
        if( is_string($link) && strlen($link) > 0 ){
            ob_start();
            include "media/svg/$icon.svg";
            $svg = ob_get_clean();
            if( $svg !== '' )
                $this->_icon = $svg;
        }
    }

}