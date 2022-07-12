<?php $__env->startSection('body-class', 'page-home'); ?>
<?php $__env->startSection('content'); ?>






<?php echo $html; ?>


<script>
  $(function() {
    $(document).on('click', '.module-edit .edit', function(event) {
      window.parent.postMessage({index: 0}, '*')
    });
  });
</script>



<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/pushuo/www/product/beikeshop/themes/default/home.blade.php ENDPATH**/ ?>