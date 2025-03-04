<script>
    const url = "{{ shop_route('account.index') }}";
    if (window.opener === null) {
        window.location.href = url;
    } else {
        window.opener.location = url;
        window.close();
    }

    if (window.name) {
        var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
        setTimeout(() => {
            parent.layer.close(index); //再执行关闭
            parent.window.location.reload()
        }, 200);
    }
</script>
