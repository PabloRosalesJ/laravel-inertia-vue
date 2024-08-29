<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { currency } from '../utils';
import { onMounted, ref, watch } from 'vue';
import { addProduct, removeProduct, requestOrderDetail, updateProduct } from '../services';

const props = defineProps({
    order: Object,
    products: Array,
});

const productsList = ref([]);
const updatingProduct = ref(false);
const addingProduct = ref(false);
const piecesCount = ref(0);
const total = ref(0);

onMounted(() => {
    productsList.value = props.order.details;
})

watch(productsList, (v, nv) => {

    piecesCount.value = productsList.value.reduce(
        (ac, val) => ac + val.quantity, 0
    );

    total.value = productsList.value.reduce(
        (ac, val) => ac + val.quantity * val.unit_price, 0
    );
})

const reHidrate = async () => {
    const data = await requestOrderDetail(props.order.id);
    productsList.value = data.order.details;
}

const handleUpdateProduct = async (pieces, orderDetail) => {

    updatingProduct.value = true;

    try {
        await updateProduct(orderDetail, pieces);
        await reHidrate();
        updatingProduct.value = false;
    } catch (error) {
        console.error(error);
        updatingProduct.value = false;
    }
}

const decrease = (product, orderDetail) => {
    const value = document.getElementById(`prod_${product}`).value
    handleUpdateProduct(Number(value) - 1, orderDetail);
}

const increase = (product, orderDetail) => {
    const value = document.getElementById(`prod_${product}`).value
    handleUpdateProduct(Number(value) + 1, orderDetail);
}

const handleRemoveProduct = async (orderDetail) => {
    updatingProduct.value = true;

    try {
        await removeProduct(orderDetail);
        await reHidrate();
        updatingProduct.value = false;
    } catch (error) {
        console.error(error);
        updatingProduct.value = false;
    }

}

const handleAddProduct = async () => {
    const select = document.getElementById('products_menu');

    if (select.value <= 0) return;

    addingProduct.value = true;
    try {
        await addProduct(props.order.id, select.value);
        await reHidrate();

        select.value = -1;
        addingProduct.value = false;
    } catch (error) {
        console.error(error);
        addingProduct.value = false;
    }
}

</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Orden #{{ order.number }}
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg flex gap-10 p-10">

                    <div class="w-4/10">
                        <div
                            class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                            <a href="#">
                                <h5 class="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                                    {{ order.client.name }}
                                </h5>
                            </a>
                            <p class="mb-3 font-normal text-gray-500 dark:text-gray-400">
                                {{ order.client.email }}
                            </p>
                            <p class="mb-3 font-normal text-gray-500 dark:text-gray-400">
                                {{ order.client.phone }}
                            </p>
                            <p class="mb-3 font-normal text-gray-500 dark:text-gray-400">
                                {{ order.client.address }}
                            </p>

                            <a href="#" class="inline-flex font-medium items-center text-blue-600 hover:underline">
                                Ver productos
                                <svg class="w-3 h-3 ms-2.5 rtl:rotate-[270deg]" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 11v4.833A1.166 1.166 0 0 1 13.833 17H2.167A1.167 1.167 0 0 1 1 15.833V4.167A1.166 1.166 0 0 1 2.167 3h4.618m4.447-2H17v5.768M9.111 8.889l7.778-7.778" />
                                </svg>
                            </a>
                        </div>

                    </div>

                    <div class="overflow-x-auto shadow-md sm:rounded-lg w-6/10 w-full">

                        <form class="mb-3 flex gap-3">
                            <div class="w-10/12">
                                <label for="products_menu"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Productos
                                </label>
                                <select id="products_menu"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">

                                    <option disabled selected value="-1"> - SELECCIONE UNO - </option>
                                    <option v-for="p in products" :key="p.id" :value="p.id">
                                        {{ p.name }} - {{ currency(p.price) }}
                                    </option>
                                </select>
                            </div>

                            <div class="w-2/12 content-end">
                                <button @click="handleAddProduct" :disabled="addingProduct" type="button"
                                    class="w-full px-5 py-2.5 text-sm font-medium text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    Agregar
                                </button>
                            </div>

                        </form>

                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>

                                    <th scope="col" class="px-6 py-3">
                                        Producto
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Pz
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Precio
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Subtotal
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Acción
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in productsList" :key="item.id"
                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                        {{ item.product.name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <button @click="decrease(item.product_id, item.id)"
                                                :disabled="updatingProduct || item.quantity <= 1"
                                                :class="[updatingProduct ? 'cursor-not-allowed' : '']"
                                                class="inline-flex items-center justify-center p-1 me-3 text-sm font-medium h-6 w-6 text-gray-500 bg-white border border-gray-300 rounded-full focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
                                                type="button">
                                                <span class="sr-only">Quantity button</span>
                                                <svg class="w-3 h-3" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 2">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2" d="M1 1h16" />
                                                </svg>
                                            </button>
                                            <div>
                                                <input :value="item.quantity"
                                                    @change="({ target: { value } }) => handleUpdateProduct(value, item.id)"
                                                    :disabled="updatingProduct" :id="`prod_${item.product_id}`"
                                                    type="number" id="first_product"
                                                    class="bg-gray-50 w-14 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-2.5 py-1 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                    placeholder="1" required />
                                            </div>
                                            <button @click="increase(item.product_id, item.id)"
                                                :disabled="updatingProduct"
                                                :class="[updatingProduct ? 'cursor-not-allowed' : '']"
                                                class="inline-flex items-center justify-center h-6 w-6 p-1 ms-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-full focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
                                                type="button">
                                                <span class="sr-only">Quantity button</span>
                                                <svg class="w-3 h-3" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                        {{ currency(item.unit_price) }}
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                        {{ currency(item.quantity * item.unit_price) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <button @click="() => handleRemoveProduct(item.id)" :disabled="updatingProduct"
                                            :class="[updatingProduct ? 'cursor-not-allowed' : '']"
                                            class="font-medium text-red-600 dark:text-red-500 hover:underline">Remove</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="bg-white rounded-lg m-4 dark:bg-gray-800">
                            <div class="w-full mx-auto max-w-screen-xl p-4 md:flex md:items-center md:justify-between">
                                <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">
                                    Productos: {{ productsList.length }}. Piezas {{ piecesCount }}
                                </span>
                                <ul
                                    class="flex flex-wrap items-center mt-3 text-sm font-medium text-gray-500 dark:text-gray-400 sm:mt-0">
                                    <li>
                                        <span class="me-4 md:me-6">Total</span>
                                    </li>
                                    <li>
                                        {{ currency(total) }}
                                    </li>
                                </ul>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </AppLayout>
</template>
