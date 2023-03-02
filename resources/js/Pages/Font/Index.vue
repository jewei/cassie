<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import { ref } from 'vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';

const props = defineProps({
    fonts: Array,
    errors: Object,
});

const form = useForm({
    font: null,
});

const fontInput = ref(null);

const selectNewFont = () => {
    fontInput.value.click();
};

const submit = () => {
    form.post(route('fonts.store'));
};

const destroy = font => {
    router.delete(route('fonts.destroy'), {
        data: { filename: font },
        onBefore: () => confirm('Are you sure you want to delete this font?'),
    });
};
</script>

<template>
    <Head title="Settings" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Settings</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white py-10 shadow-sm dark:bg-gray-100 sm:rounded-lg">
                    <div class="px-4 sm:px-6 lg:px-8">
                        <div class="sm:flex sm:items-center">
                            <div class="sm:flex-auto">
                                <h1 class="text-xl font-semibold text-gray-900">Fonts</h1>
                                <p class="mt-2 text-sm text-gray-700">
                                    A list of all the uploaded fonts that can be use in certificate PDF. (TTF mime type
                                    only)
                                </p>
                            </div>
                            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                                <Link
                                    href="#"
                                    @click.prevent="selectNewFont"
                                    as="button"
                                    type="button"
                                    :disabled="form.processing"
                                    :class="{ 'opacity-25': form.processing }"
                                    class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto"
                                >
                                    Upload
                                </Link>
                                <InputError :message="props.errors.font" class="mt-2" />

                                <form @submit.prevent="submit">
                                    <input
                                        ref="fontInput"
                                        type="file"
                                        @input="form.font = $event.target.files[0]"
                                        class="hidden"
                                        @change="submit"
                                    />
                                    <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                                        {{ form.progress.percentage }}%
                                    </progress>
                                </form>
                            </div>
                        </div>
                        <div class="mt-8 flex flex-col">
                            <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                                        <table class="min-w-full divide-y divide-gray-300">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th
                                                        scope="col"
                                                        class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6"
                                                    >
                                                        Name
                                                    </th>
                                                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                                        <span class="sr-only">Edit</span>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white">
                                                <tr
                                                    v-for="(font, idx) in fonts"
                                                    :key="idx"
                                                    :class="idx % 2 === 0 ? undefined : 'bg-gray-50'"
                                                >
                                                    <td
                                                        class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6"
                                                    >
                                                        {{ font }}
                                                    </td>
                                                    <td
                                                        class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6"
                                                    >
                                                        <Link
                                                            href="#"
                                                            @click="destroy(font)"
                                                            as="button"
                                                            type="button"
                                                            class="text-red-500 hover:text-red-700"
                                                            preserve-scroll
                                                            >Delete
                                                        </Link>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
