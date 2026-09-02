<script setup>
import { dismissNotification, notificationState } from '@/composables/useNotification';

const variants = {
    success: { accent: 'border-l-emerald-500', icon: 'bg-emerald-600 text-white', title: 'text-emerald-700' },
    error: { accent: 'border-l-red-500', icon: 'bg-red-600 text-white', title: 'text-red-600' },
    warning: { accent: 'border-l-amber-400', icon: 'bg-amber-400 text-white', title: 'text-amber-600' },
    info: { accent: 'border-l-blue-500', icon: 'bg-blue-600 text-white', title: 'text-blue-700' },
};
</script>

<template>
    <Teleport to="body">
        <div class="pointer-events-none fixed inset-x-3 top-3 z-[130] flex flex-col items-end gap-3 sm:inset-x-auto sm:right-5 sm:top-5 sm:w-[380px]" aria-live="polite" aria-atomic="false">
            <TransitionGroup enter-active-class="duration-200 ease-out" enter-from-class="translate-x-4 opacity-0" enter-to-class="translate-x-0 opacity-100" leave-active-class="duration-150 ease-in" leave-from-class="translate-x-0 opacity-100" leave-to-class="translate-x-4 opacity-0">
                <article v-for="item in notificationState.items" :key="item.id" :class="variants[item.type]?.accent" class="pointer-events-auto relative w-full rounded-lg border border-l-4 border-slate-200 bg-white px-4 py-3.5 shadow-lg" :role="item.type === 'error' ? 'alert' : 'status'">
                    <div class="flex items-start gap-3 pr-7">
                        <span :class="variants[item.type]?.icon" class="mt-0.5 grid h-7 w-7 shrink-0 place-items-center rounded-full" aria-hidden="true">
                            <svg v-if="item.type === 'success'" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m5 12 4 4L19 6"/></svg>
                            <svg v-else-if="item.type === 'warning'" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3 2.5 20h19L12 3Z"/><path d="M12 9v5m0 3h.01"/></svg>
                            <svg v-else-if="item.type === 'info'" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 11v5m0-8h.01"/></svg>
                            <svg v-else class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m7 7 10 10M17 7 7 17"/></svg>
                        </span>
                        <div class="min-w-0">
                            <h2 :class="variants[item.type]?.title" class="text-sm font-bold">{{ item.title }}</h2>
                            <p class="mt-1 text-xs leading-5 text-slate-600">{{ item.message }}</p>
                        </div>
                    </div>
                    <button type="button" class="absolute right-2.5 top-2 grid h-7 w-7 place-items-center rounded-md text-base text-slate-400 hover:bg-slate-100 hover:text-slate-700" aria-label="Tutup notifikasi" @click="dismissNotification(item.id)">×</button>
                </article>
            </TransitionGroup>
        </div>
    </Teleport>
</template>
