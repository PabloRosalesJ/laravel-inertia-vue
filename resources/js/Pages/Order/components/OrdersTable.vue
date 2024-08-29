<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3'
import { currency, getPaginatorMeta } from '../utils';
import { deleteOrder, requestPage } from '../services';

const props = defineProps({
    data: Array,
    paginator: Object,
    meta: Object,
});

/* State */
const orders = ref(props.data);
const pages = ref(props.paginator.slice(1, -1));
const pagesMeta = ref(props.meta);
const deleting = ref(false);

/* Methods */
const getPrevPage = () => {
    if (!pagesMeta.value.hasPrev) return;
    getPage(pagesMeta.value.current - 1);
}

const getNextPage = () => {
    if (!pagesMeta.value.hasNext) return;
    getPage(pagesMeta.value.current + 1);
}

const getPage = async (page, force = false) => {

    if (page == pagesMeta.value.current && !force) return;

    const data = await requestPage(page)
    orders.value = data.data;
    pages.value = data.links.slice(1, -1);
    pagesMeta.value = getPaginatorMeta(data);
}

const cancelOrder = async (order) => {
    deleting.value = true;
    await deleteOrder(order);
    await getPage(pagesMeta.value.current, true);
    deleting.value = false;
}

const showDetails = (order) => {
    const url = route('orders.show', { order })
    router.visit(url);
}

</script>

<template>
    <section class="">
        <div class="flex flex-col">
            <div class="overflow-x-auto">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden border border-gray-200 dark:border-gray-700 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th scope="col"
                                        class="py-2 px-4 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                        NO.
                                    </th>

                                    <th scope="col"
                                        class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                        Fecha
                                    </th>

                                    <th scope="col"
                                        class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                        Cliente
                                    </th>

                                    <th scope="col"
                                        class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                        Productos
                                    </th>

                                    <th scope="col"
                                        class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                        Total
                                    </th>

                                    <th scope="col" class="relative py-3.5 px-4 dark:text-gray-400">
                                        <span class="sr-only">Acciones</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-700 dark:bg-gray-900">
                                <tr v-for="o in orders" :key="o.id">
                                    <td
                                        class="px-4 py-4 text-sm font-medium text-gray-700 dark:text-gray-200 whitespace-nowrap">
                                        #{{ o.number }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">
                                        {{ o.created_at }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">
                                        <div class="flex items-center gap-x-2">
                                            <div>
                                                <h2 class="text-sm font-medium text-gray-800 dark:text-white">
                                                    {{ o.client?.name }}
                                                </h2>
                                                <p class="text-xs font-normal text-gray-600 dark:text-gray-400">
                                                    {{ o.client?.email }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-sm font-medium text-gray-800 dark:text-white">
                                        {{ o.details_count }} prod.
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 whitespace-nowrap">
                                        {{ currency(o.total_amount) }}
                                    </td>
                                    <td class="px-4 py-4 text-sm whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-3">
                                            <button @click="() => showDetails(o.id)"
                                                class="text-blue-500 transition-colors duration-200 hover:text-indigo-500 focus:outline-none">
                                                Detalles
                                            </button>

                                            <!-- <Link :href="$route('orders.show', { order: o.id })">View User</Link> -->

                                            <button @click="() => cancelOrder(o.id)" :disabled="deleting"
                                                :class="[deleting ? 'cursor-not-allowed' : '']"
                                                class="text-red-500 transition-colors duration-200 dark:hover:text-indigo-500 dark:text-red-300 hover:text-indigo-500 focus:outline-none">
                                                Cancelar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between p-3">
            <button @click="getPrevPage"
                class="flex items-center px-5 py-2 text-sm text-gray-700 capitalize transition-colors duration-200 bg-white border rounded-md gap-x-2 hover:bg-gray-100 dark:text-gray-200 dark:border-gray-700 dark:hover:bg-gray-800"
                :class="[pagesMeta.hasPrev ? 'dark:bg-gray-900' : 'dark:bg-gray-800 cursor-not-allowed']">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5 rtl:-scale-x-100">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" />
                </svg>

                <span>
                    previous
                </span>
            </button>

            <div class="items-center hidden md:flex gap-x-3">
                <button v-for="p in pages" :key="p.label" @click="getPage(p.label)"
                    class="px-2 py-1 text-sm text-blue-500 rounded-md"
                    :class="[p.active ? 'dark:bg-gray-800 bg-blue-100/60' : 'dark:hover:bg-gray-800 dark:text-gray-300 hover:bg-gray-100']">
                    {{ p.label }}
                </button>
            </div>

            <button @click="getNextPage"
                class="flex items-center px-5 py-2 text-sm text-gray-700 capitalize transition-colors duration-200 bg-white border rounded-md gap-x-2 hover:bg-gray-100 dark:text-gray-200 dark:border-gray-700 dark:hover:bg-gray-800"
                :class="[pagesMeta.hasNext ? 'dark:bg-gray-900' : 'dark:bg-gray-800 cursor-not-allowed']">
                <span>
                    Next
                </span>

                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5 rtl:-scale-x-100">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
                </svg>
            </button>
        </div>

        <p class="text-xs font-normal text-gray-600 dark:text-gray-400 px-3 mb-3">
            Mostrando de {{ pagesMeta.from }} a {{ pagesMeta.to }} de {{ pagesMeta.total }} en total.
        </p>
    </section>
</template>
