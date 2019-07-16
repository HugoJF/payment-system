import * as types from '../constants/ActionTypes';

interface AuthState {
    token?: string,
}

const auth = (state: AuthState = {}, action) => {
    switch (action.type) {
        case types.STORE_AUTH:
            return {
                ...state,
                ...action.data,
            };
        case types.DESTROY_AUTH:
            return {};
        default:
            return state
    }
};

export default auth