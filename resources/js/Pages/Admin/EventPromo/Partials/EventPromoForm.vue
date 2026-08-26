<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { computed, onBeforeUnmount, ref } from 'vue';

const props = defineProps({
    form: { type: Object, required: true },
    currentPosterUrl: { type: String, default: null },
    submitLabel: { type: String, default: 'Simpan' },
});

defineEmits(['submit']);

const localPreviewUrl = ref(null);
const previewUrl = computed(() => localPreviewUrl.value || props.currentPosterUrl);

const selectPoster = (event) => {
    const [file] = event.target.files;
    props.form.poster = file || null;

    if (localPreviewUrl.value) URL.revokeObjectURL(localPreviewUrl.value);
    localPreviewUrl.value = file ? URL.createObjectURL(file) : null;
};

onBeforeUnmount(() => {
    if (localPreviewUrl.value) URL.revokeObjectURL(localPreviewUrl.value);
});
</script>

<template>
    <form class="space-y-6" @submit.prevent="$emit('submit')">
        <div>
            <InputLabel for="judul" value="Judul" />
            <TextInput id="judul" v-model="form.judul" type="text" class="mt-1 block w-full" maxlength="150" required autofocus />
            <InputError class="mt-2" :message="form.errors.judul" />
        </div>

        <div>
            <InputLabel for="deskripsi_singkat" value="Deskripsi Singkat" />
            <textarea id="deskripsi_singkat" v-model="form.deskripsi_singkat" rows="5" maxlength="255" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
            <div class="mt-1 flex justify-between gap-3 text-xs text-gray-500"><span>Maksimal 255 karakter.</span><span>{{ form.deskripsi_singkat.length }}/255</span></div>
            <InputError class="mt-2" :message="form.errors.deskripsi_singkat" />
        </div>

        <div>
            <InputLabel for="deskripsi_lengkap" value="Deskripsi Lengkap" />
            <textarea id="deskripsi_lengkap" v-model="form.deskripsi_lengkap" rows="8" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
            <InputError class="mt-2" :message="form.errors.deskripsi_lengkap" />
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div><InputLabel for="tanggal_mulai" value="Tanggal Mulai" /><TextInput id="tanggal_mulai" v-model="form.tanggal_mulai" type="date" class="mt-1 block w-full" required /><InputError class="mt-2" :message="form.errors.tanggal_mulai" /></div>
            <div><InputLabel for="tanggal_selesai" value="Tanggal Selesai" /><TextInput id="tanggal_selesai" v-model="form.tanggal_selesai" type="date" class="mt-1 block w-full" required /><InputError class="mt-2" :message="form.errors.tanggal_selesai" /></div>
        </div>

        <div>
            <InputLabel for="link_wa" value="Nomor WhatsApp (opsional)" />
            <TextInput id="link_wa" v-model="form.link_wa" type="tel" inputmode="tel" autocomplete="tel" class="mt-1 block w-full" maxlength="24" placeholder="081234567890" />
            <p class="mt-1 text-xs text-gray-500">Masukkan nomor WhatsApp yang dapat dihubungi untuk promo ini.</p>
            <InputError class="mt-2" :message="form.errors.link_wa" />
        </div>

        <div>
            <InputLabel for="poster" value="Poster" />
            <input id="poster" type="file" accept="image/jpeg,image/png,image/webp" class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm file:mr-4 file:rounded-md file:border-0 file:bg-primary-blue file:px-4 file:py-2 file:font-semibold file:text-white" :required="!currentPosterUrl" @change="selectPoster" />
            <p class="mt-2 text-xs text-gray-500">JPG, PNG, atau WebP. Maksimal 5 MB.</p>
            <InputError class="mt-2" :message="form.errors.poster" />
            <img v-if="previewUrl" :src="previewUrl" alt="Preview poster Promo" class="mt-4 aspect-[4/5] w-full max-w-72 rounded-lg border border-gray-200 object-cover" />
        </div>

        <div class="grid gap-5 sm:grid-cols-2 sm:items-end">
            <div><InputLabel for="urutan_tampil" value="Urutan Tampil" /><TextInput id="urutan_tampil" v-model="form.urutan_tampil" type="number" min="0" class="mt-1 block w-full" required /><InputError class="mt-2" :message="form.errors.urutan_tampil" /></div>
            <label class="flex h-10 items-center gap-3 rounded-md border border-gray-300 px-3 text-sm font-medium text-gray-700"><input v-model="form.is_active" type="checkbox" class="rounded border-gray-300 text-primary-blue focus:ring-primary-blue" /> Status Aktif</label>
        </div>

        <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-6">
            <slot name="cancel" />
            <PrimaryButton :disabled="form.processing">{{ form.processing ? 'Menyimpan...' : submitLabel }}</PrimaryButton>
        </div>
    </form>
</template>
