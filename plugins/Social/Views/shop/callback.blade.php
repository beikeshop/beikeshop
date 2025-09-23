<script>
    const url = "{{ shop_route('account.index') }}";
    if (window.opener === null) {
        window.location.href = url;
    } else {
        window.opener.postMessage({ type: 'social_callback', data: 'close_window' }, '*');
        setTimeout(() => {
            window.opener.location = url;
            window.close();
        }, 200);
    }
</script>
