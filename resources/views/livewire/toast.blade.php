<div
    class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 transition-all duration-300 ease-in-out"
    style="display: none;"
    wire:key="toast"
>
    <div class="rounded-md p-4 shadow-lg {{ $this->getTypeClasses() }} max-w-md w-full">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                {!! $this->getTypeIcon() !!}
            </div>
            <div class="ml-3 flex-grow">
                <p class="text-sm font-medium text-white">
                    {{ $message }}
                </p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:load', function () {
            const toastElement = document.querySelector('[wire\\:key="toast"]');

            window.livewire.on('toast-shown', (duration) => {
                // console.log('toast', toastElement);
                toastElement.style.display = 'block';
                toastElement.classList.remove('opacity-0', 'scale-90');
                toastElement.classList.add('opacity-100', 'scale-100');

                setTimeout(() => {
                    toastElement.classList.remove('opacity-100', 'scale-100');
                    toastElement.classList.add('opacity-0', 'scale-90');

                    setTimeout(() => {
                        toastElement.style.display = 'none';
                        window.livewire.emit('hideToast');
                    }, 300);
                }, duration);
            });
        });
    </script>

</div>

