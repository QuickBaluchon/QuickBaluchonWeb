<?php
$this->_t = 'ERROR';
if (isset($cat))
    echo '<img src="https://http.cat/'.$cat.'.jpg" alt="'.$cat.'">';
else
    echo $msg;
