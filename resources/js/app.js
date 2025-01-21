import './bootstrap';

document.addEventListener('alpine:init', () => {
    Alpine.data('timer', (minutes) => ({
        totalSeconds: minutes * 60,
        init() {
            let interval = setInterval(() => {
                if (this.totalSeconds > 0) {
                    this.totalSeconds -= 1;
                } else {
                    this.$dispatch('resend-allowed')
                    clearInterval(interval);
                }
            }, 1000);
        },
        formatTime() {
            const minutes = Math.floor(this.totalSeconds / 60);
            const seconds = this.totalSeconds % 60;
            return `${minutes}:${seconds.toString().padStart(2, '0')}`;
        }

    }));
});
