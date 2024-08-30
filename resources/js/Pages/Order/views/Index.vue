<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3';
import OrdersTable from '@/Pages/Order/components/OrdersTable.vue';
import { getPaginatorMeta } from '../utils';

const props = defineProps({
    orders: Object
})

const metaPaginator = getPaginatorMeta(props.orders);

const goToNewOrder = () => {
    const url = route('orders.create')
    router.visit(url);
}

</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Ordenes
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="flex">
                        <div class="w-2/12 ml-auto p-3">
                            <button @click="goToNewOrder" type="button"
                                class="w-full px-5 py-2.5 text-sm font-medium text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Crear Orden
                            </button>
                        </div>
                    </div>
                    <OrdersTable :data=orders.data :paginator=orders.links :meta=metaPaginator />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
