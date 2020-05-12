@if (session('success'))
    <div class="alert alert-success w-100 mt-5">
        {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger w-100 mt-5">
        {{ session('error') }}
    </div>
@endif
