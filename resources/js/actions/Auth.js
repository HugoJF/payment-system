import * as types from "../constants/ActionTypes";
import {get, post} from './helpers';


export const storeAuth = (data) => (
    (dispatch) => {
        dispatch({
            type: types.STORE_AUTH,
            data: data,
        })
    }
);
export const destroyAuth = (data) => (
    (dispatch) => {
        dispatch({
            type: types.DESTROY_AUTH,
            data: data,
        })
    }
);


export const getAuth = () => (
    (dispatch) => {
        return get('auth')
            .then((data) => {
                console.log('auth info', data);

                dispatch(storeAuth(data));

                return data;
            });
    }
);

export const postAuth = (query) => (
    (dispatch) => {
        return post(`auth${query}`)
            .then((data) => {
                if (data.authed)
                    dispatch(storeAuth(data));

                return data;
            })
    }
);