<script>
  document.addEventListener('DOMContentLoaded', function () {
    @if(Session::has('tsuccess'))
      Toastify({
        text: "{{ Session::get('tsuccess') }}",
        duration: 3000,
        close: true,
        gravity: "top",
        position: 'center',
        backgroundColor: "#22c55e",
      }).showToast();
    @endif

    @if(Session::has('tdanger'))
      Toastify({
        text: "{{ Session::get('tdanger') }}",
        duration: 3000,
        close: true,
        gravity: "top",
        position: 'center',
        backgroundColor: "#e11d48",
      }).showToast();
    @endif
  });
</script>
