import Swal from 'sweetalert2';

export async function confirmDelete(options = {}) {
    const result = await Swal.fire({
        title: options.title || 'Apakah Anda yakin?',
        text: options.text || 'Data yang dihapus tidak dapat dikembalikan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: options.confirmText || 'Hapus',
        cancelButtonText: options.cancelText || 'Batal',
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        reverseButtons: true,
    });

    return result.isConfirmed;
}
