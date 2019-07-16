import * as types from "../constants/ActionTypes";
import {get, post} from './helpers';


export const storeItem = (data) => (
    (dispatch) => {
        dispatch({
            type: types.STORE_ITEM,
            data: data,
        })
    }
);

export const getInventory = (steamid) => (
    (dispatch) => {
        return get(`steam/inventory/${steamid}`)
            .then((data) => {
                dispatch(storeItem(Object.values(data)));

                return data;
            });
    }
);