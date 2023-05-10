<div class="steps-wrap">
  <div class="{{ $steps == 1 || $steps == 2 || $steps == 3 ? 'active':'' }}">
    <div class="number-wrap"><span class="number">1</span></div>
    <span class="title">{{ __('shop/steps.cart') }}</span>
  </div>
  <div class="{{ $steps == 2 || $steps == 3  ? 'active':'' }}">
    <div class="number-wrap"><span class="number">2</span></div>
    <span class="title">{{ __('shop/steps.checkout') }}</span>
  </div>
  <div class="{{ $steps == 3 ? 'active':'' }}">
    <div class="number-wrap"><span class="number">3</span></div>
    <span class="title">{{ __('shop/steps.payment') }}</span>
  </div>
</div>