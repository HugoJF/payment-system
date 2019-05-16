import * as types from '../constants/ActionTypes';
import {produce} from "immer/dist/immer";

const preferences = (state = {}, action) => (
    produce(state, draft => {
        switch (action.type) {
            case types.STORE_ORDER_PREFERENCE:
                draft[action.order_id] = action.data;

                break;
        }
    })
);

export default preferences