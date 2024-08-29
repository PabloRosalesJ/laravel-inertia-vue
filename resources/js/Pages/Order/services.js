import axios from "axios"

const ordersBackend = page => `/orders${page}`

const requestPage = async (page) => {
    const {
        data: { data }
    } = await axios.get(ordersBackend(`?page=${page}`));

    return data;
}

const deleteOrder = async (order) => {
    await axios.delete(ordersBackend(`/${order}`));
}

export {
    requestPage,
    deleteOrder
}
