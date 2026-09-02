import { reactive } from 'vue';

const defaults = {
    open: false,
    type: 'save',
    title: 'Konfirmasi',
    message: '',
    description: '',
    confirmText: 'Ya, Lanjutkan',
    cancelText: 'Batal',
};

export const confirmationState = reactive({ ...defaults });
let pendingResolve = null;

const finish = (confirmed) => {
    const resolve = pendingResolve;
    pendingResolve = null;
    confirmationState.open = false;
    resolve?.(confirmed);
};

export const requestConfirmation = (options = {}) => {
    pendingResolve?.(false);

    Object.assign(confirmationState, defaults, options, { open: true });

    return new Promise((resolve) => {
        pendingResolve = resolve;
    });
};

export const acceptConfirmation = () => finish(true);
export const cancelConfirmation = () => finish(false);

export const useConfirmation = () => ({
    confirm: requestConfirmation,
});
