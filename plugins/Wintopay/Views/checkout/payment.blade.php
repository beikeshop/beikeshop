<form class="form-horizontal" action="{{ shop_route('plugin.wintopay.pay', $order->id) }}" method=POST>
  @csrf
  <input type="hidden" name="sid" value="">
  <div class="mb-3 radio-wrap">
    @foreach (plugin_setting('wintopay.payment_type') as $item)
      <div class="radio">
        <label>
          <input type="radio" name="payment_type" class="d-none" value="{{ $item }}" @if ($loop->first) checked @endif>
          <div class="item">
            <span class="title">{{ __('Wintopay::common.' . $item) }}</span>
            <div class="icon-wrap">
              @if ($item == 'card')
                @foreach (plugin_setting('wintopay.card_type') as $card_type)
                <img src="{{ plugin_origin('wintopay','/image/' . $card_type . '.svg') }}" class="img-fluid">
                @endforeach
              @else
                <img src="{{ plugin_origin('wintopay','/image/' . $item . '.svg') }}" class="img-fluid">
              @endif
            </div>
          </div>
        </label>
      </div>
    @endforeach
  </div>
  <fieldset id="payment">
    <div class="buttons">
      <div class="pull-right">
        <input type="submit" value="{{ __('common.confirm') }}" id="button-confirm"
               data-loading-text="{{  __('common.confirm') }}" class="btn btn-primary"/>
      </div>
    </div>
  </fieldset>
</form>

@if (plugin_setting('wintopay.api') == 'test')
<script src="https://stg-gateway.wintopay.com/js/shield/v3"></script>
@else
<script src="https://js.cartadicreditopay.com/js/shield/v3"></script>
@endif

<script type="text/javascript">
  const apiType = @json(plugin_setting('wintopay.api'));

  $(function () {
    $('input[name=sid]').val(cartaDiCreditoPayShield.getSessionId());
  })
  //--></script>
<style>
  .radio-wrap {
    width: 440px;
    border: 1px solid #ececec;
    padding: 10px 20px;
  }

  .radio-wrap .radio {
    display: block;
    padding: 6px 0;
  }

  .radio-wrap .radio label {
    cursor: pointer;
    width: 100%;
  }

  .radio-wrap .radio input[type="radio"] {
    display: none;
  }

  .radio-wrap .radio input[type="radio"] + .item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-left: 30px;
    overflow: hidden;
    position: relative;
  }

  .radio-wrap .radio .title {
    white-space: nowrap;
  }

  .radio-wrap .radio .icon-wrap {
    display: flex;
    align-items: center;
    height: 28px;
    width: 100%;
    justify-content: flex-end;
  }

  .radio-wrap .radio input[type="radio"] + .item img {
    max-height: 100%;
    margin-left: 4px;
  }

  .radio-wrap .radio input[type="radio"]:checked + .item:after {
    content: '\F26A';
    position: absolute;
    font-family: 'bootstrap-icons';
    top: 50%;
    transform: translateY(-50%);
    left: 0;
    font-size: 20px;
    color: #007bff;
  }

  .radio-wrap .radio input[type="radio"] + .item:after {
    content: '\F287';
    position: absolute;
    font-family: 'bootstrap-icons';
    top: 50%;
    transform: translateY(-50%);
    left: 0;
    font-size: 20px;
    color: #ececec;
  }
</style>
