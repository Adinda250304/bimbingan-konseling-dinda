<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* Impeccable Design overrides for SweetAlert2 */
    .swal2-popup.impeccable-swal {
        border-radius: 24px !important;
        padding: 2rem !important;
        background-color: var(--color-surface, #ffffff) !important;
        color: var(--color-on-surface, #1e293b) !important;
        box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1) !important;
        border: 1px solid var(--color-outline-variant, #e2e8f0) !important;
    }
    .swal2-title.impeccable-title {
        font-family: inherit !important;
        font-weight: 700 !important;
        font-size: 1.5rem !important;
        color: var(--color-on-surface, #1e293b) !important;
    }
    .swal2-html-container.impeccable-content {
        font-family: inherit !important;
        font-size: 0.95rem !important;
        color: var(--color-on-surface-variant, #64748b) !important;
        margin-top: 0.5rem !important;
    }
    .swal2-confirm.impeccable-confirm {
        border-radius: 12px !important;
        font-weight: 700 !important;
        padding: 12px 24px !important;
        font-family: inherit !important;
    }
    .swal2-icon {
        border-width: 3px !important;
    }
</style>

<script>
    const swalConfig = {
        customClass: {
            popup: 'impeccable-swal font-poppins',
            title: 'impeccable-title',
            htmlContainer: 'impeccable-content',
            confirmButton: 'impeccable-confirm',
        },
        buttonsStyling: true,
    };

    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            Swal.fire({
                ...swalConfig,
                icon: 'success',
                title: 'Berhasil!',
                text: '{!! session('success') !!}',
                timer: 3000,
                showConfirmButton: false,
            });
        @endif

        @if(session('error'))
            Swal.fire({
                ...swalConfig,
                icon: 'error',
                title: 'Oops! Gagal',
                text: '{!! session('error') !!}',
                confirmButtonColor: '#ef4444',
            });
        @endif

        @if($errors->any())
            Swal.fire({
                ...swalConfig,
                icon: 'error',
                title: 'Validasi Gagal',
                html: `<ul style="text-align: left; list-style-type: disc; padding-left: 20px; font-size: 0.875rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                       </ul>`,
                confirmButtonColor: '#ef4444',
            });
        @endif

        @if(session('warning'))
            Swal.fire({
                ...swalConfig,
                icon: 'warning',
                title: 'Peringatan',
                text: '{!! session('warning') !!}',
                confirmButtonColor: '#eab308',
            });
        @endif

        @if(session('info'))
            Swal.fire({
                ...swalConfig,
                icon: 'info',
                title: 'Informasi',
                text: '{!! session('info') !!}',
                confirmButtonColor: '#3b82f6',
            });
        @endif
    });
</script>
