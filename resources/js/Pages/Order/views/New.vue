<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3';
import { currency } from '../utils';
import { ref } from 'vue';
import { storeOrder } from '../services';

const props = defineProps({
    products: Array,
    clients: Array,
});


/* State */


const productsList = ref([]);
const piecesCount = ref(0);
const total = ref(0);

/* Methods */


const handleAddProduct = () => {

    const product = products_menu.value

    if (product == -1) return;

    const index = productsList.value.findIndex(p => p.product == product);

    if (index != -1) {
        const productFound = productsList.value[index];
        productFound.quantity = productFound.quantity + 1;

        productsList.value[index] = productFound;
        calculateFooter();
        return;
    }

    productsList.value.push({
        product, 'quantity': 1,
    });

    calculateFooter();
}

const productName = (product) => {
    return props.products.filter(p => p.id == product)[0].name;
}

const productPrice = (product) => {
    return props.products.filter(p => p.id == product)[0].price;
}

const calculateFooter = () => {
    piecesCount.value = productsList.value.reduce((ac, e) => ac + e.quantity, 0);

    total.value = productsList.value.reduce((ac, { product, quantity }) => {
        return ac + productPrice(product) * quantity;
    }, 0);
}

const decrease = (product) => {
    const index = productsList.value.findIndex(p => p.product == product);
    const productFound = productsList.value[index];

    if (productFound.quantity == 1) return;

    productFound.quantity = productFound.quantity - 1;
    productsList.value[index] = productFound;
    calculateFooter();
}

const handleUpdateProduct = (val, product) => {
    const index = productsList.value.findIndex(p => p.product == product);
    const productFound = productsList.value[index];

    productFound.quantity = val;
    productsList.value[index] = productFound;
    calculateFooter();
}

const increase = (product) => {
    const index = productsList.value.findIndex(p => p.product == product);
    const productFound = productsList.value[index];

    productFound.quantity = productFound.quantity + 1;
    productsList.value[index] = productFound;
    calculateFooter();
}

const handleRemoveProduct = (product) => {

    const products = productsList.value.filter(p => p.product != product);
    productsList.value = products;
    calculateFooter();

}

const handleStore = async () => {
    const client = clients_menu.value

    if (client == -1) return;

    document.querySelectorAll('input, select, button').forEach(element => element.disabled = true);
    const done = () => document.querySelectorAll('input, select, button').forEach(element => element.disabled = false);

    try {
        const form = { client, 'productsList': productsList.value }
        await storeOrder(form);
        done();

        router.visit(route('dashboard'));
    } catch (error) {
        console.error(error)
        done();

    }

}


</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Nueva Orden
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-10">
                    <div class="grid grid-cols-3 gap-4">
                        <div>
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


                            <label for="clients_menu"
                                class="block mb-2 mt-2 text-sm font-medium text-gray-900 dark:text-white">
                                Clientes
                            </label>
                            <select id="clients_menu"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">

                                <option disabled selected value="-1"> - SELECCIONE UNO - </option>
                                <option v-for="c in clients" :key="c.id" :value="c.id">
                                    {{ c.name }}
                                </option>
                            </select>

                            <hr class="my-4">

                            <button @click="handleAddProduct" type="button"
                                class="w-full px-5 py-2.5 text-sm font-medium text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Añadir Producto
                            </button>
                        </div>

                        <div class="col-span-2">
                            <h2 class="text-base font-semibold text-black dark:text-white">
                                Detalles
                            </h2>

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
                                    <tr v-for="item in productsList" :key="item.product"
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                            {{ productName(item.product) }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <button @click="decrease(item.product)"
                                                    class="inline-flex items-center justify-center p-1 me-3 text-sm font-medium h-6 w-6 text-gray-500 bg-white border border-gray-300 rounded-full focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
                                                    type="button">
                                                    <span class="sr-only">Quantity button</span>
                                                    <svg class="w-3 h-3" aria-hidden="true"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 18 2">
                                                        <path stroke="currentColor" stroke-linecap="round"
                                                            stroke-linejoin="round" stroke-width="2" d="M1 1h16" />
                                                    </svg>
                                                </button>
                                                <div>
                                                    <input :value="item.quantity"
                                                        @change="({ target: { value } }) => handleUpdateProduct(value, item.product)"
                                                        type="number" id="first_product"
                                                        class="bg-gray-50 w-14 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-2.5 py-1 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                        placeholder="1" required />
                                                </div>
                                                <button @click="increase(item.product)"
                                                    class="inline-flex items-center justify-center h-6 w-6 p-1 ms-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-full focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
                                                    type="button">
                                                    <span class="sr-only">Quantity button</span>
                                                    <svg class="w-3 h-3" aria-hidden="true"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 18 18">
                                                        <path stroke="currentColor" stroke-linecap="round"
                                                            stroke-linejoin="round" stroke-width="2"
                                                            d="M9 1v16M1 9h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                            {{ currency(productPrice(item.product)) }}
                                        </td>
                                        <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                            {{ currency(item.quantity * productPrice(item.product)) }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <button @click="() => handleRemoveProduct(item.product)"
                                                class="font-medium text-red-600 dark:text-red-500 hover:underline">Remove</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="bg-white rounded-lg m-4 dark:bg-gray-800">
                                <div
                                    class="w-full mx-auto max-w-screen-xl p-4 md:flex md:items-center md:justify-between">
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

                                <button @click="handleStore" type="button" v-if="productsList.length"
                                    class="w-full px-5 py-2.5 text-sm font-medium text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    Emitir Orden
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>