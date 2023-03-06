<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import { ref } from 'vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { Listbox, ListboxButton, ListboxLabel, ListboxOption, ListboxOptions } from '@headlessui/vue';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid';

const props = defineProps({
  template: Object,
  paperFonts: Object,
  maxUploadSize: String,
  mode: String,
});

const paperFormats = ['A1', 'A2', 'A3', 'A4', 'A5', 'A6'];

const form = useForm({
  _method: props.mode === 'edit' ? 'PUT' : 'POST',
  name: props.template.name,
  image: props.template.properties?.image ?? null,
  properties: props.template.properties ?? {
    title: null,
    author: 'Cassie',
    creator: 'Cassie',
    keywords: null,
    orientation: 'L',
    format: 'A4',
    unit: 'mm',
    font: 'Quicksand-Bold.ttf',
    font_colour: 'FFFFFF',
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

  if (props.mode === 'create') {
    form.post(route('templates.store'));
  }
  if (props.mode === 'edit') {
    form.post(route('templates.update', props.template.id));
  }
};

const selectNewImage = () => {
  imageInput.value.click();
};

const updateimagePreview = () => {
  const image = imageInput.value.files[0];

  if (!image) return;

  const reader = new FileReader();

  reader.onload = e => {
    imagePreview.value = e.target.result;
  };

  reader.readAsDataURL(image);
};

const destroy = () => {
  router.delete(`/templates/${props.template.id}`, {
    onBefore: () => confirm('Are you sure you want to delete this template?'),
  });
};
</script>

<template>
  <Head title="Template" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
        {{ props.template.id ? 'Edit Template' : 'Create Template' }}
      </h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white py-10 shadow-sm dark:bg-gray-100 sm:rounded-lg">
          <div class="px-4 sm:px-6 lg:px-8">
            <form @submit.prevent="submit" class="space-y-8 divide-y divide-gray-200">
              <div class="space-y-8 divide-y divide-gray-200 sm:space-y-5">
                <div class="space-y-6 sm:space-y-5">
                  <div>
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Template details</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Configure the template here.</p>
                  </div>

                  <div class="space-y-6 sm:space-y-5">
                    <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5">
                      <label for="name" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">Name</label>
                      <div class="mt-1 sm:col-span-2 sm:mt-0">
                        <input
                          type="text"
                          name="name"
                          id="name"
                          v-model="form.name"
                          class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-xs sm:text-sm"
                        />
                        <InputError class="mt-2" :message="form.errors.name" />
                        <p class="mt-2 text-sm text-gray-500">Not shown in PDF.</p>
                      </div>
                    </div>

                    <div class="sm:grid sm:grid-cols-3 sm:items-center sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5">
                      <label for="image" class="block text-sm font-medium text-gray-700">Background </label>
                      <div class="mt-1 sm:col-span-2 sm:mt-0">
                        <div class="flex items-center">
                          <div v-show="!imagePreview && props.template.thumbnail_url" class="mt-2">
                            <img :src="props.template.thumbnail_url" class="h-20 w-20 object-cover" />
                          </div>
                          <div v-show="imagePreview" class="mt-2">
                            <span
                              class="block h-20 w-20 bg-cover bg-center bg-no-repeat"
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
                          <input ref="imageInput" type="file" class="hidden" @change="updateimagePreview" />
                        </div>
                        <InputError :message="form.errors.image" class="mt-2" />
                        <p class="mt-2 text-sm text-gray-500">
                          Prefer high quality PNG or JPG file. Max upload file size:
                          {{ maxUploadSize }}
                        </p>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="space-y-6 pt-8 sm:space-y-5 sm:pt-10">
                  <div>
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Certificate Information</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">This information will be embeded in the PDF.</p>
                  </div>
                  <div class="space-y-6 sm:space-y-5">
                    <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5">
                      <label for="title" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">Title </label>
                      <div class="mt-1 sm:col-span-2 sm:mt-0">
                        <input
                          type="text"
                          name="title"
                          id="title"
                          v-model="form.properties.title"
                          class="block w-full max-w-lg rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-xs sm:text-sm"
                        />
                        <InputError class="mt-2" :message="form.errors[`properties.title`]" />
                      </div>
                    </div>

                    <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5">
                      <label for="subject" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
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
                      <label for="author" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
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
                      <label for="creator" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
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
                      <label for="keywords" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
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
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Settings</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">The settings of the PDF.</p>
                  </div>
                  <div class="space-y-6 divide-y divide-gray-200 sm:space-y-5">
                    <div class="pt-6 sm:pt-5">
                      <div role="group" aria-labelledby="label-orientation">
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
                                  <label for="orientation-portrait" class="ml-3 block text-sm font-medium text-gray-700"
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
                      <div role="group" aria-labelledby="label-format">
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
                            <div class="max-w-lg sm:max-w-xs sm:text-sm">
                              <div class="space-y-4">
                                <Listbox as="div" v-model="form.properties.format">
                                  <div class="relative mt-1">
                                    <ListboxButton
                                      class="relative w-full max-w-lg cursor-default rounded-md border border-gray-300 bg-white py-2 pl-3 pr-10 text-left shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm"
                                    >
                                      <span class="block truncate">{{ form.properties.format }}</span>
                                      <span
                                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"
                                      >
                                        <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
                                      </span>
                                    </ListboxButton>

                                    <transition
                                      leave-active-class="transition ease-in duration-100"
                                      leave-from-class="opacity-100"
                                      leave-to-class="opacity-0"
                                    >
                                      <ListboxOptions
                                        class="absolute z-10 mt-1 max-h-60 w-full max-w-lg overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                                      >
                                        <ListboxOption
                                          as="template"
                                          v-for="paperFormat in paperFormats"
                                          :key="paperFormat"
                                          :value="paperFormat"
                                          v-slot="{ active, selected }"
                                        >
                                          <li
                                            :class="[
                                              active ? 'bg-indigo-600 text-white' : 'text-gray-900',
                                              'relative cursor-default select-none py-2 pl-3 pr-9',
                                            ]"
                                          >
                                            <span
                                              :class="[selected ? 'font-semibold' : 'font-normal', 'block truncate']"
                                              >{{ paperFormat }}</span
                                            >

                                            <span
                                              v-if="selected"
                                              :class="[
                                                active ? 'text-white' : 'text-indigo-600',
                                                'absolute inset-y-0 right-0 flex items-center pr-4',
                                              ]"
                                            >
                                              <CheckIcon class="h-5 w-5" aria-hidden="true" />
                                            </span>
                                          </li>
                                        </ListboxOption>
                                      </ListboxOptions>
                                    </transition>
                                  </div>
                                </Listbox>
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
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Name on Certificate</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                      Configure the text to be display on the certificate.
                    </p>
                  </div>
                  <div class="space-y-6 sm:space-y-5">
                    <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5">
                      <label for="font" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">Font </label>
                      <div class="mt-1 sm:col-span-2 sm:mt-0">
                        <div class="max-w-lg sm:max-w-xs sm:text-sm">
                          <div class="space-y-4">
                            <Listbox as="div" v-model="form.properties.font">
                              <div class="relative mt-1">
                                <ListboxButton
                                  class="relative w-full max-w-lg cursor-default rounded-md border border-gray-300 bg-white py-2 pl-3 pr-10 text-left shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm"
                                >
                                  <span class="block truncate">{{ form.properties.font }}</span>
                                  <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                                    <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
                                  </span>
                                </ListboxButton>

                                <transition
                                  leave-active-class="transition ease-in duration-100"
                                  leave-from-class="opacity-100"
                                  leave-to-class="opacity-0"
                                >
                                  <ListboxOptions
                                    class="absolute z-10 mt-1 max-h-60 w-full max-w-lg overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                                  >
                                    <ListboxOption
                                      as="template"
                                      v-for="paperFont in props.paperFonts"
                                      :key="paperFont"
                                      :value="paperFont"
                                      v-slot="{ active, selected }"
                                    >
                                      <li
                                        :class="[
                                          active ? 'bg-indigo-600 text-white' : 'text-gray-900',
                                          'relative cursor-default select-none py-2 pl-3 pr-9',
                                        ]"
                                      >
                                        <span :class="[selected ? 'font-semibold' : 'font-normal', 'block truncate']">{{
                                          paperFont
                                        }}</span>

                                        <span
                                          v-if="selected"
                                          :class="[
                                            active ? 'text-white' : 'text-indigo-600',
                                            'absolute inset-y-0 right-0 flex items-center pr-4',
                                          ]"
                                        >
                                          <CheckIcon class="h-5 w-5" aria-hidden="true" />
                                        </span>
                                      </li>
                                    </ListboxOption>
                                  </ListboxOptions>
                                </transition>
                              </div>
                            </Listbox>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="sm:grid sm:grid-cols-3 sm:items-start sm:gap-4 sm:border-t sm:border-gray-200 sm:pt-5">
                      <label for="font_colour" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
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
                      <label for="font_size" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
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
                      <label for="x_offset" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
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
                      <label for="y_offset" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2"
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
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    class="ml-3 inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                  >
                    Save
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
