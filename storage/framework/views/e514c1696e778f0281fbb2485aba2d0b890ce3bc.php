<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
  <script src="<?php echo e(asset('vendor/vue/2.6.12/vue.js')); ?>"></script>
  <script src="<?php echo e(asset('vendor/element-ui/2.6.2/js.js')); ?>"></script>
  <script src="<?php echo e(asset('vendor/jquery/jquery-3.6.0.min.js')); ?>"></script>
  <script src="<?php echo e(asset('vendor/layer/3.5.1/layer.js')); ?>"></script>

  

  <link href="<?php echo e(mix('/build/beike/admin/css/bootstrap.css')); ?>" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo e(asset('vendor/element-ui/2.6.2/css.css')); ?>">
  <link href="<?php echo e(mix('build/beike/admin/css/app.css')); ?>" rel="stylesheet">
  <script src="<?php echo e(mix('build/beike/admin/js/app.js')); ?>"></script>
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <title>beike admin</title>
  <?php echo $__env->yieldPushContent('header'); ?>
  
</head>
<body class="<?php echo $__env->yieldContent('body-class'); ?>">
  <!-- <div style="height: 80px; background: white;"></div> -->

  <?php if (isset($component)) { $__componentOriginal78495131b5e623bbe80547eba5c6e3af3857ec49 = $component; } ?>
<?php $component = $__env->getContainer()->make(Beike\Admin\View\Components\Header::class, []); ?>
<?php $component->withName('admin-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal78495131b5e623bbe80547eba5c6e3af3857ec49)): ?>
<?php $component = $__componentOriginal78495131b5e623bbe80547eba5c6e3af3857ec49; ?>
<?php unset($__componentOriginal78495131b5e623bbe80547eba5c6e3af3857ec49); ?>
<?php endif; ?>

  <div class="main-content">
    <aside class="sidebar navbar-expand-xs border-radius-xl">
      <?php if (isset($component)) { $__componentOriginal4a59806892d47b83351e68f269151c89d7494210 = $component; } ?>
<?php $component = $__env->getContainer()->make(Beike\Admin\View\Components\Sidebar::class, []); ?>
<?php $component->withName('admin-sidebar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4a59806892d47b83351e68f269151c89d7494210)): ?>
<?php $component = $__componentOriginal4a59806892d47b83351e68f269151c89d7494210; ?>
<?php unset($__componentOriginal4a59806892d47b83351e68f269151c89d7494210); ?>
<?php endif; ?>
    </aside>
    <div class="page-title-box"><h4 class="page-title"><?php echo $__env->yieldContent('title'); ?></h4></div>
    <div id="content">
      <div class="container-fluid p-0">
        <?php echo $__env->yieldContent('content'); ?>
      </div>
    </div>
  </div>
  <?php echo $__env->yieldPushContent('footer'); ?>
</body>
</html>
<?php /**PATH /Users/pushuo/www/product/beikeshop/resources//beike/admin/views/layouts/master.blade.php ENDPATH**/ ?>