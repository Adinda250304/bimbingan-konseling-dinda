<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '{!! session('success') !!}',
            timer: 3000,
            showConfirmButton: false,
            customClass: {
                popup: 'rounded-2xl font-poppins',
            }
        });
    });
</script>
@endif

@if(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: '{!! session('error') !!}',
            confirmButtonColor: '#ef4444',
            customClass: {
                popup: 'rounded-2xl font-poppins',
                confirmButton: 'rounded-xl px-6 py-2.5 font-medium',
            }
        });
    });
</script>
@endif

@if(session('warning'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan',
            text: '{!! session('warning') !!}',
            confirmButtonColor: '#eab308',
            customClass: {
                popup: 'rounded-2xl font-poppins',
                confirmButton: 'rounded-xl px-6 py-2.5 font-medium',
            }
        });
    });
</script>
@endif

@if(session('info'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'info',
            title: 'Informasi',
            text: '{!! session('info') !!}',
            confirmButtonColor: '#3b82f6',
            customClass: {
                popup: 'rounded-2xl font-poppins',
                confirmButton: 'rounded-xl px-6 py-2.5 font-medium',
            }
        });
    });
</script>
@endif
