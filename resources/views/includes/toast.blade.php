<script>
  document.addEventListener('DOMContentLoaded', function () {
    @if (session()->has('tsuccess'))
    Toastify({
      text: '{{ session('tsuccess') }}',
      duration: 3000,
      close: true,
      gravity: 'top',
      position: 'center',
      backgroundColor: '#22c55e',
    }).showToast();
    @endif

    @if (session()->has('tdanger'))
    Toastify({
      text: '{{ session('tdanger') }}',
      duration: 3000,
      close: true,
      gravity: 'top',
      position: 'center',
      backgroundColor: '#e11d48',
    }).showToast();
    @endif
  });
</script>
