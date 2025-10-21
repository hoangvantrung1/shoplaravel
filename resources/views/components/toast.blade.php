{{-- Toast Notification Component --}}
<div id="toast-container" class="fixed top-6 right-6 z-50 space-y-2">
    {{-- Toast messages will be dynamically inserted here --}}
</div>

<script>
class ToastManager {
    constructor() {
        this.container = document.getElementById('toast-container');
        if (!this.container) {
            this.container = document.createElement('div');
            this.container.id = 'toast-container';
            this.container.className = 'fixed top-6 right-6 z-50 space-y-2';
            document.body.appendChild(this.container);
        }
    }

    show(message, type = 'success', duration = 3000) {
        const toast = document.createElement('div');
        const toastId = 'toast-' + Date.now();
        
        const typeClasses = {
            success: 'bg-emerald-600 text-white',
            error: 'bg-red-600 text-white',
            warning: 'bg-yellow-600 text-white',
            info: 'bg-blue-600 text-white'
        };

        const icons = {
            success: 'fas fa-check-circle',
            error: 'fas fa-exclamation-circle',
            warning: 'fas fa-exclamation-triangle',
            info: 'fas fa-info-circle'
        };

        toast.id = toastId;
        toast.className = `${typeClasses[type]} px-4 py-3 rounded-lg shadow-lg text-sm flex items-center space-x-2 transform translate-x-full transition-all duration-300`;
        toast.innerHTML = `
            <i class="${icons[type]}"></i>
            <span>${message}</span>
            <button onclick="toastManager.close('${toastId}')" class="ml-2 hover:opacity-75">
                <i class="fas fa-times"></i>
            </button>
        `;

        this.container.appendChild(toast);

        // Animate in
        setTimeout(() => {
            toast.classList.remove('translate-x-full');
        }, 10);

        // Auto close
        if (duration > 0) {
            setTimeout(() => {
                this.close(toastId);
            }, duration);
        }

        return toastId;
    }

    close(toastId) {
        const toast = document.getElementById(toastId);
        if (toast) {
            toast.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }
    }

    success(message, duration = 3000) {
        return this.show(message, 'success', duration);
    }

    error(message, duration = 5000) {
        return this.show(message, 'error', duration);
    }

    warning(message, duration = 4000) {
        return this.show(message, 'warning', duration);
    }

    info(message, duration = 3000) {
        return this.show(message, 'info', duration);
    }
}

// Global instance
window.toastManager = new ToastManager();

// Helper functions for easy use
window.showToast = (message, type = 'success') => toastManager.show(message, type);
window.showSuccess = (message) => toastManager.success(message);
window.showError = (message) => toastManager.error(message);
window.showWarning = (message) => toastManager.warning(message);
window.showInfo = (message) => toastManager.info(message);
</script>
