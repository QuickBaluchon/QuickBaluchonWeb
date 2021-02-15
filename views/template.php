<!DOCTYPE html>
<html lang="fr">
  <!-- HEAD -->
  <head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSS -->
    <link rel="stylesheet" href="<?=WEB_ROOT?>media/css/bootstrap-4.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?=WEB_ROOT?>media/css/main.css">

    <?php foreach($this->_css as $css): ?>
      <link rel="stylesheet" href="<?=WEB_ROOT?>media/css/<?=$css?>.css">
    <?php endforeach; ?>


    <!-- JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-------------------------------->
    <title>QuickBaluchon</title>
    <!-- /------------------------------>
  </head>

  <!-- BODY -->
  <body>

    <!-- HEADER -->
    <header>
      <?php if(!empty($this->_header))require($this->_header); ?>
    </header>

    <!-- MAIN -->
    <main>
      <?=$content?>
    </main>

    <?php foreach($this->_js as $js): ?>
      <script src="<?=WEB_ROOT?>media/script/<?=$js?>.js"></script>
    <?php endforeach; ?>

  </body>
</html>
