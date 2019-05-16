import * as types from '../constants/ActionTypes';
import {produce} from "immer/dist/immer";

const initial = {
    color: 'blue',
    width: 'w-1/3',
    avatar: true,
};

const ui = (state = initial, action) => (
    produce(state, draft => {
        switch (action.type) {
            case types.SET_ACCENT:
                draft.color = action.color;
                break;
            case types.SET_WIDTH:
                draft.width = action.width;
                break;
            case types.SET_AVATAR:
                draft.avatar = action.visible;
        }
    })
);

export default ui