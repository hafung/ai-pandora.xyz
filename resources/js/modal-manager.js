// class ModalManager {
export class ModalManager {
    constructor() {
        this.modals = {};
        this.modalTemplate = `
            <div id="{id}" class="fixed inset-0 z-50 hidden overflow-auto bg-gray-600 bg-opacity-50 flex items-center justify-center">
                <div class="bg-white rounded-lg shadow-xl p-6 w-11/12 max-w-md mx-auto">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">{title}</h3>
                        <button onclick="modalManager.closeModal('{id}')" class="text-gray-400 hover:text-gray-600">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="modal-content">
                        {content}
                    </div>
                    {saveButton}
                </div>
            </div>
        `;
    }

    createModal(id, options = {}) {
        const { title = '', content = '', showSaveButton = false } = options;
        const saveButton = showSaveButton ? `
            <div class="mt-6 flex justify-end">
                <button onclick="modalManager.saveModal('${id}')" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    保存
                </button>
            </div>
        ` : '';

        const modalHtml = this.modalTemplate
            .replace(/{id}/g, id)
            .replace('{title}', title)
            .replace('{content}', content)
            .replace('{saveButton}', saveButton);

        document.body.insertAdjacentHTML('beforeend', modalHtml);

        this.modals[id] = {
            element: document.getElementById(id),
            closeCallback: null,
            saveCallback: null
        };
    }

    openModal(id) {
        if (this.modals[id]) {
            this.modals[id].element.classList.remove('hidden');
        }
    }

    closeModal(id) {
        if (this.modals[id]) {
            this.modals[id].element.classList.add('hidden');
            if (typeof this.modals[id].closeCallback === 'function') {
                this.modals[id].closeCallback();
            }
        }
    }

    saveModal(id) {
        if (this.modals[id] && typeof this.modals[id].saveCallback === 'function') {
            this.modals[id].saveCallback();
        }
    }

    setModalContent(id, content) {
        if (this.modals[id]) {
            const modalContent = this.modals[id].element.querySelector('.modal-content');
            modalContent.innerHTML = content;
        }
    }

    setModalCloseCallback(id, callback) {
        if (this.modals[id]) {
            this.modals[id].closeCallback = callback;
        }
    }

    setModalSaveCallback(id, callback) {
        if (this.modals[id]) {
            this.modals[id].saveCallback = callback;
        }
    }
}

// const modalManager = new ModalManager();
window.modalManager = new ModalManager();
