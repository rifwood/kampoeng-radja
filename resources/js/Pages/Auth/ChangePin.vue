<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const form = useForm({
    pin: "",
    pin_confirmation: "",
});

const submit = () => {
    form.put(route("pin.update"), {
        preserveScroll: true,
        onSuccess: () => form.reset("pin", "pin_confirmation"),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Ganti PIN" />

        <div class="mb-6">
            <p
                class="text-xs font-bold uppercase tracking-[0.18em] text-[#1769e0]"
            >
                Keamanan Akun
            </p>
            <h1 class="mt-2 text-2xl font-bold text-[#15356f]">Ganti PIN</h1>
            <p class="mt-2 text-sm leading-6 text-slate-600">
                Anda menggunakan PIN sementara. Buat PIN baru sebelum
                melanjutkan ke Dashboard.
            </p>
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="pin" value="PIN Baru" />
                <TextInput
                    id="pin"
                    v-model="form.pin"
                    type="password"
                    class="mt-1 block w-full"
                    required
                    autofocus
                    inputmode="numeric"
                    pattern="[0-9]{6}"
                    minlength="6"
                    maxlength="6"
                    autocomplete="new-password"
                />
                <InputError class="mt-2" :message="form.errors.pin" />
            </div>

            <div class="mt-4">
                <InputLabel for="pin_confirmation" value="Konfirmasi PIN" />
                <TextInput
                    id="pin_confirmation"
                    v-model="form.pin_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    required
                    inputmode="numeric"
                    pattern="[0-9]{6}"
                    minlength="6"
                    maxlength="6"
                    autocomplete="new-password"
                />
            </div>

            <div class="mt-6 flex items-center justify-between gap-3">
                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="text-sm font-semibold text-slate-500 hover:text-slate-700"
                >
                    Logout
                </Link>
                <PrimaryButton
                    :class="{ 'opacity-50': form.processing }"
                    :disabled="form.processing"
                >
                    {{ form.processing ? "Menyimpan..." : "Simpan PIN" }}
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
