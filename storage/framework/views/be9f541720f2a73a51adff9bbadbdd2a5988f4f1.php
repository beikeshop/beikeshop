<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <title>首页</title>
  <link rel="stylesheet" type="text/css" href="<?php echo e(asset('/build/beike/shop/default/css/bootstrap.css')); ?>">
  <script src="<?php echo e(asset('vendor/jquery/jquery-3.6.0.min.js')); ?>"></script>
  <script src="<?php echo e(asset('vendor/layer/3.5.1/layer.js')); ?>"></script>
  
  <script src="<?php echo e(asset('vendor/bootstrap/5.1.3/js/bootstrap.min.js')); ?>"></script>
  <script src="<?php echo e(asset('/build/beike/shop/default/js/app.js')); ?>"></script>
  <link rel="stylesheet" type="text/css" href="<?php echo e(asset('/build/beike/shop/default/css/app.css')); ?>">
  <?php echo $__env->yieldPushContent('header'); ?>
</head>
<body class="<?php echo $__env->yieldContent('body-class'); ?>">
  <?php echo $__env->make('layout.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

  <?php echo $__env->yieldContent('content'); ?>

  <?php echo $__env->make('layout.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

  <?php echo $__env->yieldPushContent('add-scripts'); ?>
</body>
</html>
<?php /**PATH /Users/pushuo/www/product/beikeshop/themes/default/layout/master.blade.php ENDPATH**/ ?>