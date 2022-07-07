<div class="steps-wrap">
  <div class="{{ $steps == 1 || $steps == 2 || $steps == 3 || $steps == 4 ? 'active':'' }}">
    <div class="number-wrap"><span class="number">1</span></div>
    <span class="title">购物车</span>
  </div>
  <div class="{{ $steps == 2 || $steps == 3 || $steps == 4 ? 'active':'' }}">
    <div class="number-wrap"><span class="number">2</span></div>
    <span class="title">结账</span>
  </div>
  <div class="{{ $steps == 3 || $steps == 4 ? 'active':'' }}">
    <div class="number-wrap"><span class="number">3</span></div>
    <span class="title">提交成功</span>
  </div>
  <div class="{{ $steps == 4 ? 'active':'' }}">
    <div class="number-wrap"><span class="number">4</span></div>
    <span class="title">付款</span>
  </div>
</div>