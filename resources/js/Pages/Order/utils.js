const currency = (number) => {
    const formatter = Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' });

    return formatter.format(number)
}

const getPaginatorMeta = (page) => {
    return {
        'current': page.current_page,
        'from': page.from,
        'to': page.to,
        'total': page.total,
        'hasPrev': page.prev_page_url !== null,
        'hasNext': page.next_page_url !== null,
    }
}


export {
    currency,
    getPaginatorMeta
}
