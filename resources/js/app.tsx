import React from 'react';
import ReactDOM from 'react-dom';
import rootReducer from './reducers/index';
import thunk from 'redux-thunk';
import {createStore, applyMiddleware, compose} from 'redux';
import {Router} from "react-router-dom";
import {Provider} from "react-redux";
import history from "./constants/history"
import {persistStore, persistReducer} from 'redux-persist'
import storage from 'redux-persist/lib/storage'
import {PersistGate} from 'redux-persist/integration/react'
import * as Sentry from '@sentry/browser';
import ErrorBoundary from "./components/ui/ErrorBoundary";
import App from "./components/App";
import 'nprogress/nprogress.css'

const composeEnhancers = (window as any).__REDUX_DEVTOOLS_EXTENSION_COMPOSE__ || compose;

// Sentry.init({
//     dsn: "https://67968fc7047c4b529fe8f41b3db647a5@sentry.io/1467304"
// });

const persistConfig = {
    key: 'root',
    whitelist: ['auth'],
    storage,
};

const persistedReducer = persistReducer(persistConfig, rootReducer);

export const store = createStore(persistedReducer, composeEnhancers(applyMiddleware(thunk)));

const persistor = persistStore(store);

ReactDOM.render(
    <Provider store={store}>
        <PersistGate loading={null} persistor={persistor}>
            <ErrorBoundary>
                <Router history={history}>
                    <App/>
                </Router>
            </ErrorBoundary>
        </PersistGate>
    </Provider>, document.getElementById('root'));

