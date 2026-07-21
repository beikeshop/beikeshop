<!-- Set up a container element for the button -->
<div id="paypal-button-container" class="mt-4"></div>

@if(($payment_setting['api_mode'] ?? 'rest') == 'nvp')
<script>
    const token = $('meta[name="csrf-token"]').attr('content')
    fetch('/paypal/create', {
        method: 'POST',
        headers: {
            'X-CSRF-Token': token
        },
        body: JSON.stringify({
            orderNumber: "{{$order->number}}",
        })
    }).then(function (res) {
        return res.json();
    }).then(function (orderData) {
        if (orderData.approval_url) {
            location = orderData.approval_url;
            return;
        }

        layer.alert(orderData.message || 'PayPal payment failed.', {
            title: '{{ __('common.text_hint') }}',
            closeBtn: 0,
            area: ['400px', 'auto'],
            btn: ['{{ __('common.confirm') }}']
        });
    });
</script>
@else
<!-- Include the PayPal JavaScript SDK -->
@if($payment_setting['sandbox_mode'])
    <script src="https://www.paypal.com/sdk/js?client-id={{ plugin_setting('paypal.sandbox_client_id') }}&currency={{ plugin_setting('paypal.currency') }}"></script>
@else
    <script src="https://www.paypal.com/sdk/js?client-id={{ plugin_setting('paypal.live_client_id') }}&currency={{ plugin_setting('paypal.currency') }}"></script>
@endif

<script>
    // Render the PayPal button into #paypal-button-container
    paypal.Buttons({
        // Call your server to set up the transaction
        createOrder: function (data, actions) {
            const token = $('meta[name="csrf-token"]').attr('content')
            return fetch('/paypal/create', {
                method: 'POST',
                headers: {
                    'X-CSRF-Token': token
                },
                body: JSON.stringify({
                    orderNumber: "{{$order->number}}",
                })
            }).then(function (res) {
                return res.json();
            }).then(function (orderData) {
                if (orderData.error) {
                    layer.alert(orderData.error.details[0].description, {
                        title: '{{ __('common.text_hint') }}',
                        closeBtn: 0,
                        area: ['400px', 'auto'],
                        btn: ['{{ __('common.confirm') }}']
                    }, function(index) {
                      window.location.reload();
                      layer.close(index);
                    });
                }
                return orderData.id;
            });
        },

        // Call your server to finalize the transaction
        onApprove: function (data, actions) {
            const token = $('meta[name="csrf-token"]').attr('content')
            return fetch('/paypal/capture', {
                method: 'POST',
                headers: {
                    'X-CSRF-Token': token
                },
                body: JSON.stringify({
                    orderNumber: "{{$order->number}}",
                    paypalOrderId: data.orderID,
                    payment_gateway_id: $("#payapalId").val(),
                })
            }).then(function (res) {
                // console.log(res.json());
                return res.json();
            }).then(function (orderData) {
                // Successful capture! For demo purposes:
                console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
                let captureStatus = orderData.status
                if (captureStatus === 'COMPLETED') {
                    location = "{{ shop_route('checkout.success', ['order_number' => $order->number]) }}"
                }
            });
        }
    }).render('#paypal-button-container');
</script>
@endif
