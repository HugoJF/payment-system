import pathToRegexp from "path-to-regexp/index";
import history from "./history"

export const compileNewUrl = function (url: string, data: object, defaults: object = {}) {
    return pathToRegexp.compile(url)({
        ...defaults,
        ...this.props.match.params,
        ...data,
    });
};

export const redirectTo = function (path: string, data: object, defaults: object = {}) {
    let url = compileNewUrl.bind(this)(path, data, defaults);

    history.push(url);
};

export const redirect = function (data: object, defaults: object = {}) {
    let redirect = redirectTo.bind(this);

    let r = path => (redirect(path, data, defaults));

    return routes.bind(this)(r);
};

export const url = function (data: object, defaults: object = {}) {
    let r = path => compileNewUrl.bind(this)(path, data, defaults);

    return routes.bind(this)(r);
};

const routes = function (r: (path: string) => string | void) {

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
    }
};