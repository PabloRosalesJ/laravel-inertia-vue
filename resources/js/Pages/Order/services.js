import axios from "axios"

const ordersBackend = page => `/orders${page}`
const detailBackend = page => `/orders-detail${page}`

const requestPage = async (page) => {
    const {
        data: { data }
    } = await axios.get(ordersBackend(`?page=${page}`));

    return data;
}

const deleteOrder = async (order) => {
    await axios.delete(ordersBackend(`/${order}`));
}

const requestOrderDetail = async (order) => {
    const { data: { data } } = await axios.get(ordersBackend(`/${order}/show`));

    return data;
}

const updateProduct = async (orderDetail, newQuantity) => {
    await axios.put(detailBackend(`/${orderDetail}`), { newQuantity })
}

const removeProduct = async (orderDetail) => {
    await axios.delete(detailBackend(`/${orderDetail}`));
}

const addProduct = async (order, product) => {
    await axios.post(detailBackend(``), { order, product });
}

const storeOrder = async (form) => {
    const { data } = await axios.post(
        ordersBackend(''),
        form
    );

    return data;
}

export {
    requestPage,
    deleteOrder,
    requestOrderDetail,
    updateProduct,
    removeProduct,
    addProduct,
    storeOrder
}
