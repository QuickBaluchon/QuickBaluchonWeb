<?php

require_once ('SidebarMenuItem.php');
require_once ('views/View.php');

class SidebarMenu extends View {

    private $_role;
    private $_html;
    private $_items;

    public function __construct($role) {
        $this->_role = $role;

        $itemsLocation = __DIR__ . '/items/items' . ucfirst($role) . '.json';

        if (file_exists($itemsLocation)) {
            $this->_items = json_decode(file_get_contents($itemsLocation), true);
            $sh = $_SESSION['defaultLang']['shortcut'];
            if (key_exists($sh, $this->_items)) {
                $this->_items = $this->_items[$sh];
            } else {
                $this->_items = $this->_items['FR'];
            }
        }

        $data = [];

        foreach ($this->_items as $link => $item)
            $data[] = new SidebarMenuItem($item['itemName'], $this->_role.'/'.$link, $item['svg']);
        $this->_html = $this->build($data);
    }

    private function build($data) {
        $view = new View();
        $li = [];
        foreach ($data as $item) {
            $li[] = $view->generateTemplate('sidebarItem', ['item' => $item]);
        }
        return $view->generateTemplate('sidebar', ['items' => $li]);
    }

    public function display() {
        echo $this->_html;
    }

}

