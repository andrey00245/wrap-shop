<form id="wayforpayForm" method="POST" action="https://secure.wayforpay.com/pay">
    @foreach ($formData as $key => $value)
        @if (is_array($value))
            @foreach ($value as $v)
                <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
            @endforeach
        @else
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endif
    @endforeach
</form>

<script>
    window.onload = function() {
        document.getElementById('wayforpayForm').submit();
    };
</script>
