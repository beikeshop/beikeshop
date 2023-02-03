<form class="form-horizontal" action="" method=POST>
  <fieldset id="payment">
    <div class="my-3" style="">{!! plugin_setting("offline_pay.comment")!!}</div>
    <div class="buttons">
      <div class="pull-right">
        <input type="button" value="{{ __('common.confirm') }}" id="button-confirm"
               data-loading-text="{{  __('common.confirm') }}" class="btn btn-primary"/>
      </div>
    </div>
  </fieldset>
</form>

<script type="text/javascript">

  $('#button-confirm').bind('click', function () {
    window.location.href = 'account/orders/' + "{{$order->number}}";
  });
  //--></script>
