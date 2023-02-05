<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import autosize from "autosize";
import { onBeforeUnmount, ref } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { onMounted } from "vue";

const props = defineProps({
    template: Object,
    datatext: String,
});

const form = useForm({
    datatext: props.datatext,
});

const textarea = ref(null);

const submit = () => form.post(route("templates.certificates.store", props.template.id));

onMounted(() => autosize(textarea.value));
onBeforeUnmount(() => autosize.destroy(textarea.value));
</script>

<template>
    <Head title="Participant" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Participant
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-100 overflow-hidden shadow-sm sm:rounded-lg py-10">
                    <form
                        @submit.prevent="submit"
                        class="space-y-8 divide-y divide-gray-200"
                    >
                        <div class="px-4 sm:px-6 lg:px-8">
                            <div class="sm:flex sm:items-center">
                                <div class="sm:flex-auto">
                                    <h1 class="text-xl font-semibold text-gray-900">
                                        Participants
                                    </h1>
                                    <p class="mt-2 text-sm text-gray-700">
                                        A list of all the certificate
                                        participants and their details in comma
                                        seperated format.
                                    </p>
                                </div>
                            </div>
                            <div class="mt-8 flex flex-col">
                                <textarea
                                    ref="textarea"
                                    v-model="form.datatext"
                                    placeholder="John Doe, john.doe@example.com
Will Smith, will.smith@example.com"
                                    class="block w-full text-sm rounded-md"
                                ></textarea>
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.datatext"
                                />
                            </div>
                            <div class="pt-5 flex justify-end">
                                <Link
                                    type="button"
                                    :href="route('templates.certificates.index', template)"
                                    class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                >
                                    Cancel
                                </Link>
                                <button
                                    type="submit"
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing"
                                    class="ml-3 inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                >
                                    Save and regenerate PDF
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
