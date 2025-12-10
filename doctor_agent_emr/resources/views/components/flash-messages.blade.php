<div>
    @if ($errors->any())
        <div class="kt-alert kt-alert-destructive" id="alert_5">
            <div class="kt-alert-title">
                <ul>
                    @foreach( $errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @session('success')
        <div class="kt-alert kt-alert-success" id="alert_5">
            <div class="kt-alert-title">
                <ul>
                    <li>{{ session('success') }}</li>
                </ul>
            </div>
        </div>
    @endsession

    @session('error')
        <div class="kt-alert kt-alert-destructive" id="alert_5">
            <div class="kt-alert-title">
                <ul>
                    <li>{{ session('error') }}</li>
                </ul>
            </div>
        </div>
    @endsession
</div>
