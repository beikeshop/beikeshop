<form class="form-horizontal needs-validation" novalidate action="{{ shop_route('plugin.wtp.pay', $order->id) }}" method=POST>
  @csrf
  <input type="hidden" name="sid" value="">
  <input type="hidden" name="payment_type" value="{{ $payment_type }}">
  <div class="mb-3">
    @foreach (plugin_setting('wtp.card_type', ['visa', 'mastercard']) as $card_type)
      <img style="height: 30px;" src="{{ plugin_origin('wtp', '/image/' . $card_type . '.svg') }}" class="img-fluid">
    @endforeach

    <div class="w-max-500 mt-3 mb-5">
      <div class="mb-3">
        <label class="form-label">{{ __('Wtp::common.card_number') }}</label>
        <input type="text" name="payment_information[card_number]" required class="form-control" placeholder="{{ __('Wtp::common.card_number') }}">
        <div class="invalid-feedback">{{ __('common.error_required', ['name' => __('Wtp::common.card_number')]) }}</div>
      </div>

      <div class="row mb-3">
        <div class="col-6">
          <label class="form-label">{{ __('Wtp::common.text_expiry') }}</label>
          <input type="text" name="payment_information[expiry_year_month]" required class="form-control expiry-year-month" placeholder="MM/YY">
          <div class="invalid-feedback">{{ __('common.error_required', ['name' => __('Wtp::common.text_expiry')]) }}</div>
        </div>

        <div class="col-6">
          <label class="form-label">{{ __('Wtp::common.cvv') }}</label>
          <input type="text" name="payment_information[cvv]" required class="form-control" placeholder="{{ __('Wtp::common.cvv') }}">
          <div class="invalid-feedback">{{ __('common.error_required', ['name' => __('Wtp::common.cvv')]) }}</div>
        </div>
      </div>


      <input type="text" name="payment_information[holder_name]" class="form-control d-none">
    </div>
  </div>
  <fieldset id="payment">
    <div class="buttons">
      <div class="pull-right">
        <input type="button" value="{{ __('common.confirm') }}" id="button-confirm"
               data-loading-text="{{  __('common.confirm') }}" class="btn btn-primary"/>
      </div>
    </div>
  </fieldset>
</form>

@if (plugin_setting('wtp.api') == 'test')
<script src="https://stg-gateway.wintopay.com/js/shield/v3"></script>
@else
<script src="https://js.cartadicreditopay.com/js/shield/v3"></script>
@endif

<script type="text/javascript">
  const apiType = @json(plugin_setting('wtp.api'));

  $(function () {
    $('input[name=sid]').val(cartaDiCreditoPayShield.getSessionId());

    $('#button-confirm').on('click', function () {
      const data = $('form').serialize();
      const url = $('form').attr('action');

      const form = $('.needs-validation')[0]; // 获取表单

      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
        $(form).addClass('was-validated');
        return;
      }

      $http.post(url, data).then(function (res) {
        if (res.data.error) {
          layer.alert(res.data.error, {
            title: '{{ __('common.text_hint') }}',
            icon: 2,
            btn: ['{{ __('common.confirm') }}'],
          });
        }

        if (res.data.redirect_url) {
          window.location.href = res.data.redirect_url;
        }

        if (res.data.message) {
          layer.alert(res.data.message, {
            title: '{{ __('common.text_hint') }}',
            icon: 1,
            btn: ['{{ __('common.confirm') }}'],
            yes: function () {
              window.location.href = '{{ shop_route('checkout.success', ['order_number' => $order->number]) }}';
            }
          });
        }
      })
    });
  })


  $('.expiry-year-month').on('input', function (event) {
    let value = $(this).val();
    const inputType = event.originalEvent.inputType;

    // 只允许输入数字和 /
    value = value.replace(/[^0-9/]/g, '');

    // 禁止首位输入 '/'
    if (value.startsWith('/')) {
      value = '';
    }

    // 禁止第二位输入 '/'
    if (value.length >= 2 && value.charAt(1) === '/') {
      value = value.charAt(0);
    }

    // 如果输入的前两位超过12，自动修正为12
    let month = parseInt(value.slice(0, 2), 10);
    if (!isNaN(month) && month > 12) {
      value = '12' + (value.length > 2 ? '/' + value.slice(3) : '');
    }

    // 第三位只能是 '/'
    if (value.length >= 3 && value.charAt(2) !== '/') {
      value = value.slice(0, 2) + '/' + value.slice(2).replace('/', '');
    }

    // 自动补全 '/'，但只在非删除操作时
    if (value.length === 2 && inputType !== 'deleteContentBackward') {
      value += '/';
    }

    // 限制最大长度为 5 (MM/YY)
    if (value.length > 5) {
      value = value.slice(0, 5);
    }

    $(this).val(value);
  });

  $('input[name="payment_information[cvv]"]').on('input', function() {
    const value = $(this).val();

    // Only allow numbers
    $(this).val(value.replace(/[^\d]/g, ''));

    // Limit to 3 digits
    if (value.length > 3) {
      $(this).val(value.slice(0, 3));
    }
  });

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
