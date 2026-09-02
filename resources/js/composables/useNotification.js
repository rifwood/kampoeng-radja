import { reactive } from 'vue';

export const notificationState = reactive({
    items: [],
});

let nextId = 0;
const timers = new Map();

const durationByType = {
    success: 3500,
    error: 4000,
    warning: 4500,
    info: 4000,
};

const titleByType = {
    success: 'Berhasil',
    error: 'Terjadi Kesalahan',
    warning: 'Peringatan',
    info: 'Informasi',
};

export const dismissNotification = (id) => {
    const index = notificationState.items.findIndex((item) => item.id === id);
    if (index !== -1) notificationState.items.splice(index, 1);
    window.clearTimeout(timers.get(id));
    timers.delete(id);
};

export const notify = ({ type = 'info', title, message, duration } = {}) => {
    if (!message) return null;

    const id = ++nextId;
    notificationState.items.push({
        id,
        type,
        title: title || titleByType[type] || titleByType.info,
        message,
    });

    const timeout = duration ?? durationByType[type] ?? durationByType.info;
    if (timeout > 0) {
        timers.set(id, window.setTimeout(() => dismissNotification(id), timeout));
    }

    return id;
};

export const useNotification = () => ({
    notify,
    success: (message, options = {}) => notify({ ...options, type: 'success', message }),
    error: (message, options = {}) => notify({ ...options, type: 'error', message }),
    warning: (message, options = {}) => notify({ ...options, type: 'warning', message }),
    info: (message, options = {}) => notify({ ...options, type: 'info', message }),
    dismiss: dismissNotification,
});
