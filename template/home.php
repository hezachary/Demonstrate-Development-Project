<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">

  <title><?php echo $app->getMeta('AUTHOR');?></title>

  <!-- Bootstrap core CSS -->
  <link href="<?php echo $app->getMeta('BASEURLPATH');?>assests/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="<?php echo $app->getMeta('BASEURLPATH');?>assests/css/style.css" rel="stylesheet">

  <script>
    //<![CDATA[
    var basepath = <?php echo json_encode($app->getMeta('BASEURLPATH'));?>;
    //]]>
  </script>
</head>

<body>

<div class="container">
  <div class="row">
    <form class="form-search" name="search" action="" method="POST">
      <h2 class="form-search-heading">Search</h2>
      <input type="hidden" name="mode" value="submit" />
      <input type="hidden" name="total" value="<?php echo $this->aryExtra['total'];?>" />
        <?php $this->loadTemplate( 'home.keywords', true );?>
        <?php $this->loadTemplate( 'home.matchwords', true );?>
      <button class="btn btn-lg btn-primary btn-block" type="submit">Submit</button>
    </form>
  </div>
  <div id="result">

  </div>
</div> <!-- /container -->

<!-- jQuery -->
<script src="<?php echo $app->getMeta('BASEURLPATH');?>assests/js/jquery.1.11.1.min.js"></script>
<script defer src="<?php echo $app->getMeta('BASEURLPATH');?>assests/js/project.js"></script>
</body>
</html>
