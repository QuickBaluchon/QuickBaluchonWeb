<?php

class View
{
    private $_file;
    private $_header = '';
    private $_css = [];
    public $_js = [];
    private $_contentFile ;
    public $_content = [] ;
    public $_template = [] ;

    public function __construct($action = null) {
        if( $action ) {
            $this->_file = 'views/view' . $action . '.php';
        }
    }

    public function generateView($data = null) {
        /*$this->_contentFile = 'views/content/view' . $action . '.json' ;
        $content = json_decode(file_get_contents($this->_contentFile), true);
        $sh = $_SESSION['defaultLang']['shortcut'] ;
        if (key_exists($sh, $content))
            $this->_content = $content[$sh];
        else
            $this->_content = $content["FR"];*/

        $html = $this->generateFile($this->_file, $data);

        $view = $this->generateFile('views/template.php',
            [
                'header' => $this->_header,
                'content' => $html
            ]);
        echo $view;
    }

    public function generateTemplate($file, $data) {
        $templateFile = 'templates/content/' . $file . '.json' ;
        if (file_exists($templateFile)) {
            $template = json_decode(file_get_contents($templateFile), true);
            $sh = $_SESSION['defaultLang']['shortcut'];
            if (key_exists($sh, $template))
                $this->_template = $template[$sh];
            else
                $this->_template = $template["FR"];
        }

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
