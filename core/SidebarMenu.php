<?php

require_once ('SidebarMenuItem.php');
require_once ('views/View.php');

class SidebarMenu extends View {

    private $_role;
    private $_html;

    public function __construct($role) {
        $this->_role = $role;

        if( method_exists($this, $role) )
            $this->$role();
    }

    private function build($data) {
        $view = new View();
        $li = [];
        foreach ($data as $item) {
            $li[] = $view->generateTemplate('sidebarItem', ['item' => $item]);
        }
        return $view->generateTemplate('sidebar', ['items' => $li]);
    }

    public function dispay() {
        echo $this->_html;
    }

    private function client() {
        $items = ['Données personnelles', 'Factures', 'Historique'];
        $links = ['profile', 'bills', 'history'];
        $svg = ['user', 'invoice', 'open-book'];
        $data = [];

        for( $i = 0; $i < count($items); $i++ )
            $data[] = new SidebarMenuItem($items[$i], $this->_role.'/'.$links[$i], $svg[$i]);

        $this->_html = $this->build($data);

    }

    private function deliveryman() {
        $items = ['Données personnelles', 'Statistiques', 'Fiches de paie'];
        $links = ['profile', 'statistics', 'payslip'];
        $svg = ['user', 'speedometer', 'invoice' ];
        $data = [];

        for( $i = 0; $i < count($items); $i++ )
            $data[] = new SidebarMenuItem($items[$i], $this->_role.'/'.$links[$i], $svg[$i]);

        $this->_html = $this->build($data);

    }

}

