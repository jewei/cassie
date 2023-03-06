<script setup>
import { ref } from 'vue';
import { Dialog, DialogPanel } from '@headlessui/vue';
import { Bars3Icon, XMarkIcon } from '@heroicons/vue/24/outline';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import InputError from '@/Components/InputError.vue';
import { Link, useForm } from '@inertiajs/vue3';

const mobileMenuOpen = ref(false);

const props = defineProps({
  url: String,
});

const form = useForm({
  uuid: '',
});

const submit = () => {
  form.post(route('search.search'), {
    onFinish: () => form.reset(),
  });
};
</script>

<template>
  <div class="isolate bg-white">
    <div class="absolute inset-x-0 top-[-10rem] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[-20rem]">
      <svg
        class="relative left-[calc(50%-11rem)] -z-10 h-[21.1875rem] max-w-none -translate-x-1/2 rotate-[30deg] sm:left-[calc(50%-30rem)] sm:h-[42.375rem]"
        viewBox="0 0 1155 678"
      >
        <path
          fill="url(#45de2b6b-92d5-4d68-a6a0-9b9b2abad533)"
          fill-opacity=".3"
          d="M317.219 518.975L203.852 678 0 438.341l317.219 80.634 204.172-286.402c1.307 132.337 45.083 346.658 209.733 145.248C936.936 126.058 882.053-94.234 1031.02 41.331c119.18 108.451 130.68 295.337 121.53 375.223L855 299l21.173 362.054-558.954-142.079z"
        />
        <defs>
          <linearGradient
            id="45de2b6b-92d5-4d68-a6a0-9b9b2abad533"
            x1="1155.49"
            x2="-78.208"
            y1=".177"
            y2="474.645"
            gradientUnits="userSpaceOnUse"
          >
            <stop stop-color="#9089FC" />
            <stop offset="1" stop-color="#FF80B5" />
          </linearGradient>
        </defs>
      </svg>
    </div>
    <div class="px-6 pt-6 lg:px-8">
      <nav class="flex items-center justify-between" aria-label="Global">
        <div class="flex lg:flex-1">
          <div class="flex shrink-0 items-center">
            <Link :href="route('search.index')">
              <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
            </Link>
          </div>
        </div>
        <div class="flex lg:hidden">
          <button
            type="button"
            class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700"
            @click="mobileMenuOpen = true"
          >
            <span class="sr-only">Open main menu</span>
            <Bars3Icon class="h-6 w-6" aria-hidden="true" />
          </button>
        </div>
        <div class="hidden lg:flex lg:flex-1 lg:justify-end">
          <Link :href="route('login')" class="text-sm font-semibold leading-6 text-gray-900"
            >Log in <span aria-hidden="true">&rarr;</span></Link
          >
        </div>
      </nav>
      <Dialog as="div" @close="mobileMenuOpen = false" :open="mobileMenuOpen">
        <DialogPanel focus="true" class="fixed inset-0 z-10 overflow-y-auto bg-white px-6 py-6 lg:hidden">
          <div class="flex items-center justify-between">
            <Link :href="route('search.index')" class="-m-1.5 p-1.5">
              <span class="sr-only">Cassie</span>
              <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
            </Link>
            <button type="button" class="-m-2.5 rounded-md p-2.5 text-gray-700" @click="mobileMenuOpen = false">
              <span class="sr-only">Close menu</span>
              <XMarkIcon class="h-6 w-6" aria-hidden="true" />
            </button>
          </div>
          <div class="mt-6 flow-root">
            <div class="-my-6 divide-y divide-gray-500/10">
              <div class="py-6">
                <Link
                  :href="route('login')"
                  class="-mx-3 block rounded-lg py-2.5 px-3 text-base font-semibold leading-6 text-gray-900 hover:bg-gray-400/10"
                >
                  Log in</Link
                >
              </div>
            </div>
          </div>
        </DialogPanel>
      </Dialog>
    </div>
    <main>
      <div class="relative px-6 lg:px-8">
        <div class="py-32 sm:py-48 lg:py-56">
          <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div
              class="relative isolate overflow-hidden bg-indigo-900 px-6 py-24 shadow-2xl sm:rounded-3xl sm:px-24 xl:py-32"
            >
              <h2 class="mx-auto max-w-2xl text-center text-3xl font-bold tracking-tight text-white sm:text-4xl">
                Verify a certificate.
              </h2>
              <p class="mx-auto mt-2 max-w-xl text-center text-lg leading-8 text-gray-300">
                The Certificate ID can be found at the bottom of each certificate.
              </p>
              <form @submit.prevent="submit" class="mx-auto mt-10 flex max-w-md gap-x-4">
                <label for="uuid" class="sr-only">Certificate ID</label>
                <input
                  id="uuid"
                  name="uuid"
                  type="text"
                  v-model="form.uuid"
                  autofocus
                  class="min-w-0 flex-auto rounded-md border border-white/10 bg-white/5 px-[calc(theme(spacing.4)-1px)] py-[calc(theme(spacing[1.5])-1px)] text-base leading-7 text-white placeholder-gray-500 shadow-sm focus:border-white focus:ring-white"
                  placeholder="Enter Certificate ID"
                />
                <button
                  type="submit"
                  class="flex-none rounded-md bg-white py-1.5 px-3.5 text-base font-semibold leading-7 text-gray-900 shadow-sm hover:bg-gray-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white"
                >
                  Validate
                </button>
              </form>
              <InputError class="mx-auto mt-4 flex max-w-md" :message="form.errors.uuid" />
              <a v-if="url" :href="props.url" class="mx-auto mt-4 flex max-w-md font-semibold text-green-500">Valid!</a>
              <svg
                viewBox="0 0 1024 1024"
                class="absolute top-1/2 left-1/2 -z-10 h-[64rem] w-[64rem] -translate-x-1/2"
                aria-hidden="true"
              >
                <circle
                  cx="512"
                  cy="512"
                  r="512"
                  fill="url(#759c1415-0410-454c-8f7c-9a820de03641)"
                  fill-opacity="0.7"
                />
                <defs>
                  <radialGradient
                    id="759c1415-0410-454c-8f7c-9a820de03641"
                    cx="0"
                    cy="0"
                    r="1"
                    gradientUnits="userSpaceOnUse"
                    gradientTransform="translate(512 512) rotate(90) scale(512)"
                  >
                    <stop stop-color="#7775D6" />
                    <stop offset="1" stop-color="#E935C1" stop-opacity="0" />
                  </radialGradient>
                </defs>
              </svg>
            </div>
          </div>
        </div>
        <div
          class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]"
        >
          <svg
            class="relative left-[calc(50%+3rem)] h-[21.1875rem] max-w-none -translate-x-1/2 sm:left-[calc(50%+36rem)] sm:h-[42.375rem]"
            viewBox="0 0 1155 678"
          >
            <path
              fill="url(#ecb5b0c9-546c-4772-8c71-4d3f06d544bc)"
              fill-opacity=".3"
              d="M317.219 518.975L203.852 678 0 438.341l317.219 80.634 204.172-286.402c1.307 132.337 45.083 346.658 209.733 145.248C936.936 126.058 882.053-94.234 1031.02 41.331c119.18 108.451 130.68 295.337 121.53 375.223L855 299l21.173 362.054-558.954-142.079z"
            />
            <defs>
              <linearGradient
                id="ecb5b0c9-546c-4772-8c71-4d3f06d544bc"
                x1="1155.49"
                x2="-78.208"
                y1=".177"
                y2="474.645"
                gradientUnits="userSpaceOnUse"
              >
                <stop stop-color="#9089FC" />
                <stop offset="1" stop-color="#FF80B5" />
              </linearGradient>
            </defs>
          </svg>
        </div>
      </div>
    </main>
  </div>
  <footer class="mx-auto max-w-7xl px-6 pb-8 pt-16 sm:pt-24 lg:px-8 lg:pt-32">
    <h2 id="footer-heading" class="sr-only">Footer</h2>
    <div class="mt-16 border-t border-gray-900/10 pt-8 sm:mt-20 lg:mt-24">
      <p class="text-xs leading-5 text-gray-500">&copy; 2023 Jewei Mak. All rights reserved.</p>
    </div>
  </footer>
</template>
