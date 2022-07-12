<?php $__env->startSection('title', '后台管理'); ?>

<?php $__env->startSection('content'); ?>
  
  <?php for($i = 0; $i < 10; $i++): ?>
    <div class="card mb-3">
      <div class="card-header">订单统计</div>
      <div class="card-body">
        <div>11</div>
      </div>
    </div>
  <?php endfor; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin::layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/pushuo/www/product/beikeshop/resources//beike/admin/views/pages/home.blade.php ENDPATH**/ ?>