<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { ChevronDownIcon } from '@heroicons/vue/20/solid'
import { Head, Link, router } from "@inertiajs/vue3";

const props = defineProps({
    template: Object,
    participants: Array,
});

const LinkMenuitems = [
    { name: 'Edit Template', href: route('templates.edit', [props.template.id]), method: 'GET' },
    { name: 'Regenerate PDF', href: route('templates.regenerate', [props.template.id]), method: 'POST' },
]

const exportMenuitems = [
    { name: 'Download CSV', href: route('exports.participants', [props.template.id]) },
    { name: 'Download ZIP', href: route('exports.certificates', [props.template.id]) },
]
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
                                <Link :href="route('templates.index')"
                                    class="mx-4 font-medium text-gray-500 hover:text-gray-700">Back
                                </Link>
                                <Link v-if="participants.length < 1" :href="route('templates.certificates.create', props.template)" type="button"
                                    class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">Add Participant
                                </Link>
                                <div v-if="participants.length > 0" class="inline-flex rounded-md shadow-sm">
                                    <Link :href="route('templates.certificates.create', props.template)" type="button" class="relative inline-flex items-center rounded-l-md border border-gray-300 bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Edit Participant</Link>
                                    <Menu as="div" class="relative -ml-px block">
                                        <MenuButton
                                            class="relative inline-flex items-center rounded-r-md border border-gray-300 bg-white px-2 py-2 text-sm font-medium text-gray-500 hover:bg-gray-50 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                            <span class="sr-only">Open options</span>
                                            <ChevronDownIcon class="h-5 w-5" aria-hidden="true" />
                                        </MenuButton>
                                        <transition enter-active-class="transition ease-out duration-100"
                                            enter-from-class="transform opacity-0 scale-95"
                                            enter-to-class="transform opacity-100 scale-100"
                                            leave-active-class="transition ease-in duration-75"
                                            leave-from-class="transform opacity-100 scale-100"
                                            leave-to-class="transform opacity-0 scale-95">
                                            <MenuItems
                                                class="absolute right-0 z-10 mt-2 -mr-1 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                                <div class="py-1">
                                                    <MenuItem v-for="item in LinkMenuitems" :key="item.name" v-slot="{ active }">
                                                        <Link :href="item.href" :method="item.method" as="button" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">{{item.name}}</Link>
                                                    </MenuItem>
                                                    <MenuItem v-for="item in exportMenuitems" :key="item.name" v-slot="{ active }">
                                                        <a :href="item.href" :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">{{item.name}}</a>
                                                    </MenuItem>
                                                </div>
                                            </MenuItems>
                                        </transition>
                                    </Menu>
                                </div>
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
                                                    v-for="(participant, idx) in props.participants"
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
                                                            :href="route('templates.certificates.show', [props.template.id, participant.id])"
                                                            class="text-indigo-600 hover:text-indigo-900"
                                                            preserve-scroll
                                                            >View
                                                        </a>
                                                        <a 
                                                            v-if="participant.rendered_at"
                                                            :href="route('templates.certificates.show', [props.template.id, participant.id]) + '?download=1'"
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
