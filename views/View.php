<?php

class View {
  private $_file;
  private $_header = '';
  private $_css = [];
  private $_js = [];

  public function __construct($action) {
    $this->_file = 'views/view'. $action . '.php';
  }

  public function generate($data) {
    $content = $this->generateFile($this->_file, $data);
    $view = $this->generateFile('views/template.php',
      [ 'header' => $this->_header,
        'content' => $content
      ]);

    echo $view;
  }

  private function generateFile( $file, $data ) {
    if( file_exists($file) ) {
      extract($data);

      ob_start();
      require $file;
      return ob_get_clean();
    }else
      throw new Exception("File " . $file . 'not found');

  }
}
