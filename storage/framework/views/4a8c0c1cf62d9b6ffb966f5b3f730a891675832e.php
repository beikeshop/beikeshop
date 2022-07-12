<div class="header-wrap">
  <div class="header-left">
    <div class="logo">
      <img src="<?php echo e(asset('image/logo.png')); ?>" class="img-fluid">
      
    </div>
  </div>
  <div class="header-right">
    <ul class="navbar navbar-right">
      <?php $__currentLoopData = $links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li class="nav-item <?php echo e($link['active'] ? 'active' : ''); ?>"><a href="<?php echo e($link['url']); ?>" class="nav-link"><?php echo e($link['title']); ?></a></li>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
    <ul class="navbar">
      <li class="nav-item"><a href="<?php echo e(admin_route('logout.index')); ?>" class="nav-link">退出登录</a></li>
      <li class="nav-item">

        <a href="" class="nav-link">
          <img src="http://dummyimage.com/100x100" class="avatar img-fluid rounded-circle me-1">
          <span class="text-dark ml-2"><?php echo e(auth()->user()->name); ?></span>
        </a>
      </li>
    </ul>
  </div>
</div>
<?php /**PATH /Users/pushuo/www/product/beikeshop/resources//beike/admin/views/components/header.blade.php ENDPATH**/ ?>