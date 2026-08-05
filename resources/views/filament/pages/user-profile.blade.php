<x-filament-panels::page>
    <x-filament-panels::form wire:submit="save">
        {{ $this->form }}
        
        <div class="mt-4">
            <x-filament::button type="submit">
                Save Changes
            </x-filament::button>
        </div>
    </x-filament-panels::form>

    @if(session('show_biodata_modal'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    title: 'Profile Update Required!',
                    text: 'You have not completed your profile biodata. Please update your profile to proceed with paper submission.',
                    imageUrl: '{{ asset("images/facepalm_alert.png") }}',
                    imageWidth: 200,
                    imageAlt: 'Facepalm',
                    confirmButtonText: 'Got it, I will update now!',
                    confirmButtonColor: '#1b5e20',
                    customClass: {
                        popup: 'rounded-2xl',
                        title: 'text-2xl font-bold text-gray-800',
                    }
                });
            });
        </script>
    @endif
</x-filament-panels::page>
