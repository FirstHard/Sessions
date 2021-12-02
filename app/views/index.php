<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="<?= HOME ?>/app/views/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= HOME ?>/app/views/assets/css/main.css" rel="stylesheet">
    <title><?= $user_brand_title; ?> - Autorization tests</title>
  </head>
  <body>
  <div class="body-wrapper">
    <?php
        ob_start();
        include (ROOT . '/app/views/layouts/' . $layout . '.php');
        $page_template = ob_get_contents();
        ob_end_flush();
    ?>
  </div>
  <script src="<?= HOME ?>/app/views/assets/js/bootstrap.bundle.min.js"></script>
  </body>
</html>