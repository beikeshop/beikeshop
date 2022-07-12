<div class="">
  <ul class="list-unstyled navbar-nav">
    <?php $__currentLoopData = $links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <li class="nav-item <?php echo e($link['active'] ? 'active' : ''); ?>">
        <a class="nav-link" href="<?php echo e($link['url']); ?>"><i class="iconfont">&#xe65c;</i> <?php echo e($link['title']); ?></a>
      </li>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </ul>
</div>
<?php /**PATH /Users/pushuo/www/product/beikeshop/resources//beike/admin/views/components/sidebar.blade.php ENDPATH**/ ?>