<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import OrdersTable from '@/Pages/Order/components/OrdersTable.vue';
import { getPaginatorMeta } from '../utils';

const props = defineProps({
    orders: Array
})

const metaPaginator = getPaginatorMeta(props.orders);

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
                    <OrdersTable :data=orders.data :paginator=orders.links :meta=metaPaginator />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
