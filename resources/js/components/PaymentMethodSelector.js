import React, {Component, Fragment} from 'react';
import {Link, withRouter} from "react-router-dom";
import {redirect, url} from "../constants/Routes";
import {setAccent} from "../actions/UI";
import {connect} from "react-redux";
import {getOrder, initMercadoPagoOrder, initPayPalOrder, initSteamOrder} from "../actions/Order";
import NProgress from "nprogress"

// TODO: remove external image
function Loader(props) {
    return <Fragment>
        <img className={`${props.loading ? '' : 'hidden'} relative mt-4 w-20 h-20`} src="https://www.advisory.com/assets/responsive/images/loading1.gif"/>
        <img className={`${props.loading ? 'hidden' : ''} max-w-full`} src={props.url}/>
    </Fragment>
}

class PaymentMethodSelector extends Component {
    state = {
        selected: false,
    };

    constructor() {
        super();

        this.initMercadoPago = this.initMercadoPago.bind(this);
        this.initPayPal = this.initPayPal.bind(this);
        this.initSteam = this.initSteam.bind(this);
    }

    componentWillMount() {
        this.props.setAccent('blue');
    }

    initPayPal() {
        let orderId = this.props.order.public_id;

        NProgress.start();

        this.setState({
            selected: 'paypal',
        });

        this.props.initPayPalOrder(orderId)
            .then(() => {
                NProgress.set(0.5);
                this.props.getOrder(orderId).then(() => {
                    NProgress.done();
                });
            });
    }

    initMercadoPago() {
        // TODO: check for order
        let orderId = this.props.order.public_id;

        NProgress.start();

        this.setState({
            selected: 'mporder',
        });

        this.props.initMercadoPagoOrder(orderId)
            .then(() => {
                NProgress.set(0.5);
                this.props.getOrder(orderId).then(() => {
                    NProgress.done();
                });
            });
    }

    initSteam() {
        let {order} = this.props;

        // TODO: update this shit
        if (!order.payer_steam_id)
            alert('This order is unabled to be paid with Steam items');

        NProgress.start();

        this.setState({
            selected: 'steamorder',
        });

        this.props.initSteamOrder(order.public_id)
            .then(() => {
                NProgress.set(0.5);
                this.props.getOrder(order.public_id)
                    .then(() => {
                        NProgress.done();
                    });
            });

    }


    render() {
        let {selected} = this.state;

        return (
            <div className="flex flex-wrap p-4 justify-center items-stretch sm:p-6 sm:pt-20">
                <p className="mb-2 p-4 w-full text-center text-2xl text-grey-darkest">Escolha seu método de pagamento:</p>
                <div className="w-full sm:w-1/2 p-4 text-4xl">
                    <a onClick={this.initPayPal} className="trans h-32 flex py-3 px-10 sm:px-5 justify-center items-center text-grey-darkest rounded-lg bg-grey-lightest no-underline hover:shadow hover:bg-white">
                        <Loader
                            loading={selected === 'paypal'}
                            url="https://logodownload.org/wp-content/uploads/2014/10/paypal-logo.png"
                        />
                    </a>
                </div>
                <div className="w-full sm:w-1/2 p-4 text-4xl">
                    <a onClick={this.initMercadoPago} className="trans h-32 flex py-3 px-10 sm:px-5 justify-center items-center text-grey-darkest cursor-pointer rounded-lg bg-grey-lightest no-underline active:shadow-md hover:shadow hover:bg-white">
                        <Loader
                            loading={selected === 'mporder'}
                            url="http://www.freelogovectors.net/wp-content/uploads/2019/02/Mercadopago-logo.png"
                        />
                    </a>
                </div>
                <div className="w-full sm:w-1/2 p-4 text-4xl">
                    <a className="opacity-50 trans h-32 flex py-3 px-10 sm:px-5 justify-center items-center text-grey-darkest rounded-lg bg-grey-lightest no-underline">
                        <img className="max-w-full" src="https://cdn-images-1.medium.com/max/1200/1*NarjT54CL02HHKsSiw68zQ.png"/>
                    </a>
                </div>
                <div onClick={this.initSteam} className="w-full sm:w-1/2 p-4 text-4xl">
                    <a href="#" className="trans h-32 flex py-3 px-10 sm:px-5 justify-center items-center text-grey-darkest rounded-lg bg-grey-lightest no-underline hover:shadow hover:bg-white">
                        <Loader
                            loading={selected === 'steamorder'}
                            url="https://logodownload.org/wp-content/uploads/2014/09/counter-strike-global-offensive-cs-go-logo.png"
                        />
                    </a>
                </div>
            </div>
        );
    }
}

const mapDispatchToProps = dispatch => ({
    getOrder: (id) => dispatch(getOrder(id)),
    setAccent: (color) => dispatch(setAccent(color)),
    initMercadoPagoOrder: (orderId) => dispatch(initMercadoPagoOrder(orderId)),
    initPayPalOrder: (orderId) => dispatch(initPayPalOrder(orderId)),
    initSteamOrder: (orderId) => dispatch(initSteamOrder(orderId))
});

const mapStateToProps = state => ({});

export default withRouter(connect(
    mapStateToProps, mapDispatchToProps
)(PaymentMethodSelector));