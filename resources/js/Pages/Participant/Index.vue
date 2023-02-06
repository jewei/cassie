<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";

defineProps({
    template: Object,
    participants: Array,
});
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
                    <div class="px-4 sm:px-6 lg:px-8">
                        <div class="sm:flex sm:items-center">
                            <div class="sm:flex-auto">
                                <h1 class="text-xl font-semibold text-gray-900">
                                    Participants
                                </h1>
                                <p class="mt-2 text-sm text-gray-700">
                                    A list of all the certificate templates.
                                </p>
                            </div>
                            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                                <Link
                                    :href="route('templates.index')"
                                    class="mx-4 font-medium text-gray-500 hover:text-gray-700"
                                    >Back
                                </Link>
                                <Link
                                    :href="route('templates.certificates.create', template)"
                                    type="button"
                                    class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto"
                                >
                                    {{ participants.length > 0 ? "Edit Participant" : "Add Participant" }}
                                </Link>
                            </div>
                        </div>
                        <div class="mt-8 flex flex-col">
                            <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                                        <table class="min-w-full divide-y divide-gray-300">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                        Name
                                                    </th>
                                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                        Email
                                                    </th>
                                                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                                        <span class="sr-only">Edit</span>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white">
                                                <tr
                                                    v-for="(participant, idx) in participants"
                                                    :key="participant.id"
                                                    :class="idx % 2 === 0 ? undefined : 'bg-gray-50'"
                                                >
                                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                                        {{ participant.name }}
                                                    </td>
                                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                        {{ participant.email }}
                                                    </td>
                                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                                        <a
                                                            v-if="participant.rendered_at"
                                                            :href="route('templates.certificates.show', [template.id, participant.id])"
                                                            class="text-indigo-600 hover:text-indigo-900"
                                                            preserve-scroll
                                                            >View
                                                        </a>
                                                        <a 
                                                            v-if="participant.rendered_at"
                                                            :href="route('templates.certificates.show', [template.id, participant.id]) + '?download=1'"
                                                            class="ml-2 text-indigo-600 hover:text-indigo-900"
                                                            preserve-scroll
                                                            >Download
                                                        </a>
                                                        <span v-if="!participant.rendered_at" @click="router.reload({ only: ['participants'] })" class="text-gray-500">Generating...</span>
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
