<script>
    const url = "{{ shop_route('account.index') }}";
    if (window.opener === null) {
        window.location.href = url;
    } else {
        window.opener.location = url;
        window.close();
    }
</script>
