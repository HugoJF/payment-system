import {combineReducers} from "redux";
import auth from "./auth";
import storage from "redux-persist/lib/storage";
import {persistReducer} from "redux-persist";
import ui from "./ui";
import orders from "./orders";
import inventory from "./inventory";

const authConfig = {
    key: 'auth',
    whitelist: ['token'],
    storage,
};

const RootReducer = combineReducers({
    auth: persistReducer(authConfig, auth),
    inventory: inventory,
    ui: ui,
    orders: orders,
});

export default RootReducer