import * as types from "../constants/ActionTypes";
import {get, post} from './helpers';


export const storeOrder = (data) => (
    (dispatch) => {
        dispatch({
            type: types.STORE_ORDER,
            data: data,
        })
    }
);

export const storeOrderPreference = (id, preference) => (
    (dispatch) => {
        dispatch({
            type: types.STORE_ORDER_PREFERENCE,
            order_id: id,
            data: preference,
        })
    }
);

export const storePayPalLink = (id, data) => (
    (dispatch) => {
        dispatch({
            type: types.STORE_PAYPAL_LINK,
            order_id: id,
            paypal_link: data.paypal_link,
        })
    }
);

export const getOrder = (id) => (
    (dispatch) => {
        return get(`orders/${id}`)
            .then((data) => {
                console.log('auth info', data);

                dispatch(storeOrder(data));

                return data;
            });
    }
);


export const initMercadoPagoOrder = (id) => (
    (dispatch) => {
        // TODO: POST? PATCH?
        return get(`orders/${id}/mercadopago`)
            .then((data) => {
                dispatch(storeOrderPreference(id, data));

                return data;
            });
    }
);

export const initPayPalOrder = (id) => (
    (dispatch) => {
        // TODO: POST? PATCH?
        return get(`orders/${id}/paypal`)
            .then((data) => {
                dispatch(storePayPalLink(id, data));

                return data;
            });
    }
);

export const initSteamOrder = (id) => (
    (dispatch) => {
        // TODO: POST? PATCH?
        return get(`orders/${id}/steam`)
            .then((data) => {
                return data;
            });
    }
);

export const executeSteamOrder = (id, items) => (
    (dispatch) => {
        return post(`orders/${id}/steam/execute`, {items: items})
            .then((data) => {
                return data;
            })
    }
);


export const executePayPalOrder = (id) => (
    (dispatch) => {
        // TODO: POST? PATCH?
        return get(`orders/${id}/paypal/execute`)
            .then((data) => {
                return data;
            });
    }
);

export const executeMpOrder = (id) => (
    (dispatch) => {
        // TODO: POST? PATCH?
        return get(`orders/${id}/mercadopago/execute`)
            .then((data) => {
                return data;
            });
    }
);