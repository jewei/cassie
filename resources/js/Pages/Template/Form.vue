<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import { ref } from "vue";
import { Head, Link, useForm, router } from "@inertiajs/vue3";

const props = defineProps({
    template: Object,
    maxUploadSize: String,
    mode: String,
});

const form = useForm({
    _method: props.mode === "edit" ? "PUT" : "POST",
    name: props.template.name,
    image: props.template.properties?.image ?? null,
    properties: props.template.properties ?? {
        title: null,
        author: "FrogAsia Sdn. Bhd.",
        creator: "FrogAsia Sdn. Bhd.",
        keywords: null,
        orientation: "L",
        format: "A4",
        unit: "mm",
        font: "Quicksand-Bold.ttf",
        font_colour: "FFFFFF",
        font_size: 120,
        x_offset: 0,
        y_offset: -150,
    },
});

const imagePreview = ref(null);
const imageInput = ref(null);

const submit = () => {
    if (imageInput.value) {
        form.image = imageInput.value.files[0];
    }

    if (props.mode === "create") {
        form.post(route("templates.store"));
    }
    if (props.mode === "edit") {
        form.post(route("templates.update", props.template.id));
    }
};

const selectNewImage = () => {
    imageInput.value.click();
};

const updateimagePreview = () => {
    const image = imageInput.value.files[0];

    if (!image) return;

    const reader = new FileReader();

    reader.onload = (e) => {
        imagePreview.value = e.target.result;
    };

    reader.readAsDataURL(image);
};

const destroy = () => {
    router.delete(`/templates/${props.template.id}`, {
        onBefore: () =>
            confirm("Are you sure you want to delete this template?"),
    });
};
</script>

<template>
    <Head title="Template" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Edit Template
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-100 overflow-hidden shadow-sm sm:rounded-lg py-10">
                    <div class="px-4 sm:px-6 lg:px-8">
                        <form
                            @submit.prevent="submit"
                            class="space-y-8 divide-y divide-gray-200"
                        >
                            <div class="space-y-8 divide-y divide-gray-200 sm:space-y-5">
                                <div class="space-y-6 sm:space-y-5">
                                    <div>
                                        <h3 class="text-lg font-medium leading-6 text-gray-900">
                                            Template details
                                        </h3>
                                        <p class="mt-1 max-w-2xl text-sm text-gray-500">
                                            Configure the template here.
                                        </p>
                                    </div>

                                    <div class="space-y-6 sm:space-y-5">
                                        <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5">
                                            <label
                                                for="name"
                                                class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                                                >Name</label
                                            >
                                            <div class="mt-1 sm:col-span-2 sm:mt-0">
                                                <input
                                                    type="text"
                                                    name="name"
                                                    id="name"
                                                    v-model="form.name"
                                                    class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-xs sm:text-sm"
                                                />
                                                <InputError
                                                    class="mt-2"
                                                    :message="form.errors.name"
                                                />
                                                <p class="mt-2 text-sm text-gray-500">
                                                    Not shown in PDF.
                                                </p>
                                            </div>
                                        </div>

                                        <div class="sm:grid sm:grid-cols-3 sm:items-center sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5">
                                            <label
                                                for="image"
                                                class="block text-sm font-medium text-gray-700"
                                                >Background
                                            </label>
                                            <div class="mt-1 sm:col-span-2 sm:mt-0">
                                                <div class="flex items-center">
                                                    <div
                                                        v-show="!imagePreview && props.template.thumbnail_url"
                                                        class="mt-2"
                                                    >
                                                        <img
                                                            :src="props.template.thumbnail_url"
                                                            class="h-20 w-20 object-cover"
                                                        />
                                                    </div>
                                                    <div
                                                        v-show="imagePreview"
                                                        class="mt-2"
                                                    >
                                                        <span
                                                            class="block w-20 h-20 bg-cover bg-no-repeat bg-center"
                                                            :style="'background-image: url(\'' + imagePreview + '\');'"
                                                        />
                                                    </div>
                                                    <button
                                                        type="button"
                                                        @click.prevent="selectNewImage"
                                                        class="ml-5 rounded-md border border-gray-300 bg-white py-2 px-3 text-sm font-medium leading-4 text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                                    >
                                                        Select
                                                    </button>
                                                    <input
                                                        ref="imageInput"
                                                        type="file"
                                                        class="hidden"
                                                        @change="updateimagePreview"
                                                    />
                                                </div>
                                                <InputError
                                                    :message="form.errors.image"
                                                    class="mt-2"
                                                />
                                                <p class="mt-2 text-sm text-gray-500">
                                                    Prefer high quality PNG or
                                                    JPG file. Max upload file
                                                    size: {{ maxUploadSize }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-6 pt-8 sm:space-y-5 sm:pt-10">
                                    <div>
                                        <h3 class="text-lg font-medium leading-6 text-gray-900">
                                            Certificate Information
                                        </h3>
                                        <p class="mt-1 max-w-2xl text-sm text-gray-500">
                                            This information will be embeded in
                                            the PDF.
                                        </p>
                                    </div>
                                    <div class="space-y-6 sm:space-y-5">
                                        <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5">
                                            <label
                                                for="title"
                                                class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                                                >Title
                                            </label>
                                            <div class="mt-1 sm:col-span-2 sm:mt-0">
                                                <input
                                                    type="text"
                                                    name="title"
                                                    id="title"
                                                    v-model="form.properties.title"
                                                    class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-xs sm:text-sm"
                                                />
                                                <InputError
                                                    class="mt-2"
                                                    :message="form.errors[`properties.title`]"
                                                />
                                            </div>
                                        </div>

                                        <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5">
                                            <label
                                                for="subject"
                                                class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                                                >Subject
                                            </label>
                                            <div class="mt-1 sm:col-span-2 sm:mt-0">
                                                <input
                                                    type="text"
                                                    name="subject"
                                                    id="subject"
                                                    v-model="form.properties.subject"
                                                    class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-xs sm:text-sm"
                                                />
                                            </div>
                                        </div>

                                        <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5">
                                            <label
                                                for="author"
                                                class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                                                >Author
                                            </label>
                                            <div class="mt-1 sm:col-span-2 sm:mt-0">
                                                <input
                                                    type="text"
                                                    name="author"
                                                    id="author"
                                                    v-model="form.properties.author"
                                                    class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-xs sm:text-sm"
                                                />
                                            </div>
                                        </div>

                                        <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5">
                                            <label
                                                for="creator"
                                                class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                                                >Creator
                                            </label>
                                            <div class="mt-1 sm:col-span-2 sm:mt-0">
                                                <input
                                                    type="text"
                                                    name="creator"
                                                    id="creator"
                                                    v-model="form.properties.creator"
                                                    class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-xs sm:text-sm"
                                                />
                                            </div>
                                        </div>

                                        <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5">
                                            <label
                                                for="keywords"
                                                class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                                                >Keywords
                                            </label>
                                            <div class="mt-1 sm:col-span-2 sm:mt-0">
                                                <input
                                                    type="text"
                                                    name="keywords"
                                                    id="keywords"
                                                    v-model="form.properties.keywords"
                                                    class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-xs sm:text-sm"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-6 divide-y divide-gray-200 pt-8 sm:space-y-5 sm:pt-10">
                                    <div>
                                        <h3 class="text-lg font-medium leading-6 text-gray-900">
                                            Settings
                                        </h3>
                                        <p class="mt-1 max-w-2xl text-sm text-gray-500">
                                            The settings of the PDF.
                                        </p>
                                    </div>
                                    <div class="space-y-6 divide-y divide-gray-200 sm:space-y-5">
                                        <div class="pt-6 sm:pt-5">
                                            <div
                                                role="group"
                                                aria-labelledby="label-orientation"
                                            >
                                                <div class="sm:grid sm:grid-cols-3 sm:items-baseline sm:gap-4">
                                                    <div>
                                                        <div
                                                            class="text-base font-medium text-gray-900 sm:text-sm sm:text-gray-700"
                                                            id="label-orientation"
                                                        >
                                                            Orientation
                                                        </div>
                                                    </div>
                                                    <div class="sm:col-span-2">
                                                        <div class="max-w-lg">
                                                            <div class="mt-4 space-y-4">
                                                                <div class="flex items-center">
                                                                    <input
                                                                        id="orientation-landscape"
                                                                        name="orientation"
                                                                        type="radio"
                                                                        value="L"
                                                                        v-model="form.properties.orientation"
                                                                        class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                                    />
                                                                    <label
                                                                        for="orientation-landscape"
                                                                        class="ml-3 block text-sm font-medium text-gray-700"
                                                                        >Landscape
                                                                    </label>
                                                                </div>
                                                                <div class="flex items-center">
                                                                    <input
                                                                        id="orientation-portrait"
                                                                        name="orientation"
                                                                        type="radio"
                                                                        value="P"
                                                                        v-model="form.properties.orientation"
                                                                        class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                                    />
                                                                    <label
                                                                        for="orientation-portrait"
                                                                        class="ml-3 block text-sm font-medium text-gray-700"
                                                                        >Portrait
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="pt-6 sm:pt-5">
                                            <div
                                                role="group"
                                                aria-labelledby="label-format"
                                            >
                                                <div class="sm:grid sm:grid-cols-3 sm:items-baseline sm:gap-4">
                                                    <div>
                                                        <div
                                                            class="text-base font-medium text-gray-900 sm:text-sm sm:text-gray-700"
                                                            id="label-format"
                                                        >
                                                            Paper Format
                                                        </div>
                                                    </div>
                                                    <div class="sm:col-span-2">
                                                        <div class="max-w-lg">
                                                            <p class="text-sm text-gray-500">
                                                                Only supporting
                                                                A4 for now.
                                                            </p>
                                                            <div class="mt-4 space-y-4">
                                                                <div class="flex items-center">
                                                                    <input
                                                                        id="format"
                                                                        name="format"
                                                                        type="radio"
                                                                        value="A4"
                                                                        v-model="form.properties.format"
                                                                        class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                                    />
                                                                    <label
                                                                        for="format"
                                                                        class="ml-3 block text-sm font-medium text-gray-700"
                                                                        >A4
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-6 pt-8 sm:space-y-5 sm:pt-10">
                                    <div>
                                        <h3 class="text-lg font-medium leading-6 text-gray-900">
                                            Name on Certificate
                                        </h3>
                                        <p class="mt-1 max-w-2xl text-sm text-gray-500">
                                            Configure the text to be display on
                                            the certificate.
                                        </p>
                                    </div>
                                    <div class="space-y-6 sm:space-y-5">
                                        <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5">
                                            <label
                                                for="font"
                                                class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                                                >Font
                                            </label>
                                            <div
                                                class="mt-1 sm:col-span-2 sm:mt-0"
                                            >
                                                <input
                                                    type="text"
                                                    name="font"
                                                    id="font"
                                                    v-model="form.properties.font"
                                                    class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-xs sm:text-sm"
                                                />
                                            </div>
                                        </div>

                                        <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5">
                                            <label
                                                for="font_colour"
                                                class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                                                >Font Colour
                                            </label>
                                            <div class="mt-1 sm:col-span-2 sm:mt-0">
                                                <input
                                                    type="text"
                                                    name="font_colour"
                                                    id="font_colour"
                                                    v-model="form.properties.font_colour"
                                                    class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-xs sm:text-sm"
                                                />
                                            </div>
                                        </div>

                                        <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5">
                                            <label
                                                for="font_size"
                                                class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                                                >Font Size
                                            </label>
                                            <div class="mt-1 sm:col-span-2 sm:mt-0">
                                                <input
                                                    type="number"
                                                    name="font_size"
                                                    id="font_size"
                                                    v-model="form.properties.font_size"
                                                    class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-xs sm:text-sm"
                                                />
                                            </div>
                                        </div>

                                        <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5">
                                            <label
                                                for="x_offset"
                                                class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                                                >X Offset
                                            </label>
                                            <div class="mt-1 sm:col-span-2 sm:mt-0">
                                                <input
                                                    type="number"
                                                    name="x_offset"
                                                    id="x_offset"
                                                    v-model="form.properties.x_offset"
                                                    class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-xs sm:text-sm"
                                                />
                                            </div>
                                        </div>

                                        <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5">
                                            <label
                                                for="y_offset"
                                                class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
                                                >Y Offset
                                            </label>
                                            <div class="mt-1 sm:col-span-2 sm:mt-0">
                                                <input
                                                    type="number"
                                                    name="y_offset"
                                                    id="y_offset"
                                                    v-model="form.properties.y_offset"
                                                    class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-xs sm:text-sm"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-5">
                                <div class="flex justify-end">
                                    <Link
                                        v-if="template.id"
                                        @click="destroy"
                                        href="#"
                                        as="button"
                                        class="mr-4 text-red-500 hover:text-red-700"
                                        preserve-scroll
                                        >Delete
                                    </Link>
                                    <Link
                                        type="button"
                                        :href="route('templates.index')"
                                        class="rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                    >Cancel
                                    </Link>
                                    <button
                                        type="submit"
                                        :class="{'opacity-25': form.processing,}"
                                        :disabled="form.processing"
                                        class="ml-3 inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                    >Save
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
