<?php extract(collect($attributes->getAttributes())->mapWithKeys(function ($value, $key) { return [Illuminate\Support\Str::camel(str_replace([':', '.'], ' ', $key)) => $value]; })->all(), EXTR_SKIP); ?>

<?php if (isset($component)) { $__componentOriginala753308f2daa78de54da9d719292e0df85686008 = $component; } ?>
<?php $component = $__env->getContainer()->make(\Beike\Admin\View\DesignBuilders\SlideShow::class, []); ?>
<?php $component->withName('editor-slide_show'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['attributes' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($attributes)]); ?>

<?php echo e($slot ?? ""); ?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala753308f2daa78de54da9d719292e0df85686008)): ?>
<?php $component = $__componentOriginala753308f2daa78de54da9d719292e0df85686008; ?>
<?php unset($__componentOriginala753308f2daa78de54da9d719292e0df85686008); ?>
<?php endif; ?><?php /**PATH /Users/pushuo/www/product/beikeshop/storage/framework/views/92e60f53934937169898b21b13fb2c43243954b4.blade.php ENDPATH**/ ?>