import * as types from "../constants/ActionTypes";


export const setAccent = (color) => (
    (dispatch) => {
        dispatch({
            type: types.SET_ACCENT,
            color: color,
        })
    }
);

export const setAvatar = (visible) => (
    (dispatch) => {
        dispatch({
            type: types.SET_AVATAR,
            visible: visible,
        })
    }
);

export const setWidth = (width) => (
    (dispatch) => {
        dispatch({
            type: types.SET_WIDTH,
            width: width,
        })
    }
);