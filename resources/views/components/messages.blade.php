@if (session('success'))
  <div id="success-message" class="bg-green-500 text-white px-4 py-2 rounded-md">
      {{ session('success') }}
  </div>
@endif
@if (session('error') || $errors->any())
    <div id="error-message" class="bg-red-500 text-white px-4 py-2 rounded-md">
        @if (session('error'))
            <p>{{ session('error') }}</p>
        @endif
        @if ($errors->any())
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
    </div>
@endif

@push('scripts')
<script>
window.onload = function() {
    setTimeout(function() {
        fadeOut(document.getElementById('success-message'));
        fadeOut(document.getElementById('error-message'));
    }, 2000);  // 2 seconds
};

function fadeOut(element) {
    element.style.transition = "opacity 0.5s ease-out";
    element.style.opacity = 0;
    setTimeout(function() {
        element.style.display = 'none';
    }, 500);  // 0.5 seconds
}
</script>
@endpush