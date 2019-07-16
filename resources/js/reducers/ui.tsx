import * as types from '../constants/ActionTypes';
import {produce} from "immer/dist/immer";

const initial = {
    color: 'grey',
    width: 'w-1/3',
    avatar: true,
    visible: true,
    url: '',
};

const ui = (state = initial, action) => (
    produce(state, draft => {
        switch (action.type) {
            case types.SET_ACCENT:
                if (action.color)
                    draft.color = action.color;
                else
                    draft.color = initial.color;
                break;
            case types.SET_WIDTH:
                if (action.width)
                    draft.width = action.width;
                else
                    draft.width = initial.width;
                break;
            case types.SET_AVATAR:
                if (action.url)
                    draft.url = action.url;

                if (action.visible)
                    draft.avatar = action.visible;
                else
                    draft.avatar = initial.visible;
                break;
        }
    })
);

export default ui