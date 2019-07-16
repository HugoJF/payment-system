import * as types from '../constants/ActionTypes';
import {produce} from "immer/dist/immer";

const orders = (state = {}, action) => (
    produce(state, draft => {
        switch (action.type) {
            case types.STORE_ORDER:
                if (!Array.isArray(action.data))
                    action.data = [action.data];

                action.data.forEach((e) => {
                    draft[e.id] = e;
                });

                break;
            case types.STORE_ORDER_PREFERENCE:
                draft[action.order_id].preference = action.data;
                break;
            case types.STORE_PAYPAL_LINK:
                draft[action.order_id].orderable.paypal_link = action.paypal_link;
                break;
        }
    })
);

export default orders