<div class="quantity-wrap">
  <input type="text" class="form-control" onkeyup="this.value=this.value.replace(/\D/g,'')" value="{{ isset($quantity) ? $quantity : 1 }}" name="quantity" minimum="{{ isset($minimum) ? $minimum : 1 }}">
  <div class="right">
    <i class="bi bi-chevron-up"></i>
    <i class="bi bi-chevron-down"></i>
  </div>
</div>