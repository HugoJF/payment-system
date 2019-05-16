import pathToRegexp from "path-to-regexp/index";
import history from "../constants/history.js"
import {matchPath} from "react-router";

export const compileNewUrl = function (url, data, defaults = {}) {
    return pathToRegexp.compile(url)({
        ...defaults,
        ...this.props.match.params,
        ...data,
    });
};

export const redirectTo = function (path, data, defaults = {}) {
    let url = compileNewUrl.bind(this)(path, data, defaults);

    history.push(url);
};

export const redirect = function (data, defaults = {}) {
    let redirect = redirectTo.bind(this);

    let r = path => (redirect(path, data, defaults));

    return routes.bind(this)(r);
};

export const url = function (data, defaults = {}) {
    let r = path => compileNewUrl.bind(this)(path, data, defaults);

    return routes.bind(this)(r);
};

const routes = function (r) {

    return {
        home: () => (
            r('/')
        ),
        paypal: () => (
            r('/order/1')
        ),
        steam: () => ({
            pending: () => (
                r('/orders/:orderId/pending')
            ),
        }),
        matches: () => ({
            index: () => (
                r('/matches')
            ),
            show: () => (
                r('/matches/:matchId')
            )

        }),
    }
};