<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
  <link href="<?php echo e(mix('/build/beike/admin/css/bootstrap.css')); ?>" rel="stylesheet">
  
  <link href="<?php echo e(mix('build/beike/admin/css/app.css')); ?>" rel="stylesheet">
  <title>admin login</title>
</head>
<body class="page-login">
  <div class="d-flex align-items-center vh-100 pt-2 pt-sm-5 pb-4 pb-sm-5">
    <div class="container">
      <div class="card">
        <div class="w-480">
          <div class="card-header mt-3 mb-4">
            <h4 class="fw-bold">登录到 beikeshop 后台</h4>
            <div class="text-muted fw-normal">登录到 beikeshop 后台</div>
          </div>

          <div class="card-body">
            <form action="<?php echo e(admin_route('login.store')); ?>" method="post">
              <?php echo csrf_field(); ?>

              <div class="form-floating mb-4">
                <input type="text" name="email" class="form-control" id="email-input" value="<?php echo e(old('email')); ?>" placeholder="邮箱地址">
                <label for="email-input">邮箱地址</label>
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                  <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'admin::components.form.error','data' => ['message' => $message]]); ?>
<?php $component->withName('admin::form.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['message' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($message)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
              </div>

              <div class="form-floating mb-5">
                <input type="password" name="password" class="form-control" id="password-input" placeholder="密码">
                <label for="password-input">密码</label>
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                  <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'admin::components.form.error','data' => ['message' => $message]]); ?>
<?php $component->withName('admin::form.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['message' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($message)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
              </div>

              <?php if(session('error')): ?>
                <div class="alert alert-success">
                  <?php echo e(session('error')); ?>

                </div>
              <?php endif; ?>

              <div class="d-grid mb-4"><button type="submit" class="btn btn-lg btn-primary">登录</button></div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
<?php /**PATH /Users/pushuo/www/product/beikeshop/resources//beike/admin/views/pages/login/login.blade.php ENDPATH**/ ?>