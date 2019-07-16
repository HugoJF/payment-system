import * as types from '../constants/ActionTypes';
import {produce} from "immer/dist/immer";

const orders = (state = {}, action) => (
    produce(state, draft => {
        switch (action.type) {
            case types.STORE_ITEM:
                if (!Array.isArray(action.data))
                    action.data = [action.data];

                action.data.forEach((e) => {
                    draft[e.assetid] = e;
                });

                break;
        }
    })
);

export default orders