<?php

class View
{
    private $_file;
    private $_header = '';
    private $_css = [];
    public $_js = [];

    public function __construct($action = null) {
        if( $action )
            $this->_file = 'views/view' . $action . '.php';
    }

    public function generateView($data = null) {
        $content = $this->generateFile($this->_file, $data);
        $view = $this->generateFile('views/template.php',
            [
                'header' => $this->_header,
                'content' => $content
            ]);

        echo $view;
    }

    public function generateTemplate($file, $data) {
        return $this->generateFile('templates/' . $file . '.php', $data);
    }

    private function generateFile($file, $data=null){
        if (file_exists($file)) {
            if( $data != null )
                extract($data);

            ob_start();
            require $file;
            return ob_get_clean();
        } else
            throw new Exception("File " . $file . 'not found');

    }
}
