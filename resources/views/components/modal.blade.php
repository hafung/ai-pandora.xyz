<div id="{{ $id }}" class="fixed inset-0 z-50 hidden overflow-auto bg-gray-600 bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl p-6 w-11/12 max-w-md mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
            <button onclick="closeModal('{{ $id }}')" class="text-gray-400 hover:text-gray-600">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="modal-content">
            {{ $slot }}
        </div>
        @if($showSaveButton)
            <div class="mt-6 flex justify-end">
                <button onclick="saveModal('{{ $id }}')" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    保存
                </button>
            </div>
        @endif
    </div>
</div>

@once
    @push('scripts')
        <script>
            // alert(45345) // would not be called？ but other funcs normal？？
            function openModal(id) {
                document.getElementById(id).classList.remove('hidden');
            }

            function closeModal(id) {
                document.getElementById(id).classList.add('hidden');
                if (typeof window[`${id}CloseCallback`] === 'function') {
                    window[`${id}CloseCallback`]();
                }
            }

            function saveModal(id) {
                if (typeof window[`${id}SaveCallback`] === 'function') {
                    window[`${id}SaveCallback`]();
                }
            }

            function setModalContent(id, content) {
                const modalContent = document.querySelector(`#${id} .modal-content`);
                modalContent.innerHTML = content;
            }

            function setModalCloseCallback(id, callback) {
                window[`${id}CloseCallback`] = callback;
            }

            function setModalSaveCallback(id, callback) {
                window[`${id}SaveCallback`] = callback;
            }
        </script>
    @endpush
@endonce
