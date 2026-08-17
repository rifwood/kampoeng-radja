<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { computed, onBeforeUnmount, ref } from 'vue';

const props = defineProps({
    form: {
        type: Object,
        required: true,
    },
    currentImageUrl: {
        type: String,
        default: null,
    },
    submitLabel: {
        type: String,
        default: 'Simpan',
    },
});

defineEmits(['submit']);

const localPreviewUrl = ref(null);
const previewUrl = computed(() => localPreviewUrl.value || props.currentImageUrl);

const selectImage = (event) => {
    const [file] = event.target.files;
    props.form.foto = file || null;

    if (localPreviewUrl.value) {
        URL.revokeObjectURL(localPreviewUrl.value);
    }

    localPreviewUrl.value = file ? URL.createObjectURL(file) : null;
};

onBeforeUnmount(() => {
    if (localPreviewUrl.value) {
        URL.revokeObjectURL(localPreviewUrl.value);
    }
});
</script>

<template>
    <form class="space-y-6" @submit.prevent="$emit('submit')">
        <div>
            <InputLabel for="judul" value="Judul" />
            <TextInput
                id="judul"
                v-model="form.judul"
                type="text"
                class="mt-1 block w-full"
                maxlength="150"
                required
                autofocus
            />
            <InputError class="mt-2" :message="form.errors.judul" />
        </div>

        <div>
            <InputLabel for="deskripsi" value="Deskripsi" />
            <textarea
                id="deskripsi"
                v-model="form.deskripsi"
                rows="8"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            ></textarea>
            <InputError class="mt-2" :message="form.errors.deskripsi" />
        </div>

        <div>
            <InputLabel for="tanggal_publish" value="Tanggal Publish" />
            <TextInput
                id="tanggal_publish"
                v-model="form.tanggal_publish"
                type="datetime-local"
                class="mt-1 block w-full"
                required
            />
            <InputError class="mt-2" :message="form.errors.tanggal_publish" />
        </div>

        <div>
            <InputLabel for="foto" value="Foto" />
            <input
                id="foto"
                type="file"
                accept="image/jpeg,image/png,image/webp"
                class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm file:mr-4 file:rounded-md file:border-0 file:bg-primary-blue file:px-4 file:py-2 file:font-semibold file:text-white"
                :required="!currentImageUrl"
                @change="selectImage"
            />
            <p class="mt-2 text-xs text-gray-500">JPG, PNG, atau WebP. Maksimal 5 MB.</p>
            <InputError class="mt-2" :message="form.errors.foto" />

            <img
                v-if="previewUrl"
                :src="previewUrl"
                alt="Preview foto Media & Berita"
                class="mt-4 h-52 w-full rounded-lg border border-gray-200 object-cover sm:w-80"
            />
        </div>

        <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-6">
            <slot name="cancel" />
            <PrimaryButton :disabled="form.processing">
                {{ form.processing ? 'Menyimpan...' : submitLabel }}
            </PrimaryButton>
        </div>
    </form>
</template>
