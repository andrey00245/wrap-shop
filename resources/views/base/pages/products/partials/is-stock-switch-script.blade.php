<script>
    document.addEventListener('DOMContentLoaded', function () {
        const switchEl = document.getElementById('in-stock-switch');
        if (!switchEl) {
            return;
        }

        const url = new URL(window.location.href);
        const inStock = url.searchParams.get('in_stock');

        if (inStock === '1') {
            switchEl.checked = true;
        }

        switchEl.addEventListener('change', function () {
            const url = new URL(window.location.href);

            if (this.checked) {
                url.searchParams.set('in_stock', '1');
            } else {
                url.searchParams.delete('in_stock');
            }

            window.location.href = url.toString();
        });
    });
</script>
