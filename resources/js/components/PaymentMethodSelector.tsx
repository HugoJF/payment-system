import React, {Component, Fragment} from 'react';
import {Link, withRouter} from "react-router-dom";
import {setAccent} from "../actions/UI";
import {connect} from "react-redux";
import {getOrder, initMercadoPagoOrder, initPayPalOrder, initSteamOrder} from "../actions/Order";
import NProgress from "nprogress"
import {getOrderType, Order, setAccentType, initMercadoPagoOrderType, initPayPalOrderType, initSteamOrderType} from "../constants/Types";

interface OwnProps {
}

interface DispatchProps {
    setAccent: setAccentType,
    getOrder: getOrderType,
    initPayPalOrder: initPayPalOrderType,
    initMercadoPagoOrder: initMercadoPagoOrderType,
    initSteamOrder: initSteamOrderType,
}

interface StateProps {
    order: Order,
}

interface State {
    selected?: Processor,
    loading: boolean,
}

enum Processor {
    MERCADOPAGO = 'mercadopago',
    STEAM = 'steam',
    PAYPAL = 'paypal',
    PAGSEGURO = 'pagseguro',
}

type Props = OwnProps & DispatchProps & StateProps;

// TODO: remove external image
function Loader(props) {
    return <>
        <img className={`${props.loading ? '' : 'hidden'} relative mt-4 w-20 h-20`} src="https://www.advisory.com/assets/responsive/images/loading1.gif"/>
        <img className={`${props.loading ? 'hidden' : ''} max-w-full`} src={props.url}/>
    </>
}

class PaymentMethodSelector extends Component<Props, State> {
    state = {
        selected: undefined,
        loading: false,
    };

    constructor(props: Props) {
        super(props);

        this.initMercadoPago = this.initMercadoPago.bind(this);
        this.initPayPal = this.initPayPal.bind(this);
        this.initSteam = this.initSteam.bind(this);
    }

    componentWillMount() {
        this.props.setAccent('blue');
    }

    init(processor: Processor) {
        let {order} = this.props;

        let map = {
            [Processor.PAYPAL]: this.initPayPal,
            [Processor.MERCADOPAGO]: this.initMercadoPago,
            [Processor.STEAM]: this.initSteam,
        };

        try {
            let initter = map[processor];

            // TODO: improve without alert?
            if (order.orderable_type)
                alert('Order has already been initialized');

            this.setState({selected: processor});

            initter();
        } catch (e) {
            this.setState({selected: undefined, loading: true});
        }
    }

    async initPayPal() {
        let {order} = this.props;

        NProgress.start();
        await this.props.initPayPalOrder(order.id);
        NProgress.set(0.5);
        await this.props.getOrder(order.id);
        NProgress.done();
    }

    async initMercadoPago() {
        let {order} = this.props;

        NProgress.start();
        await this.props.initMercadoPagoOrder(order.id);
        NProgress.set(0.5);
        await this.props.getOrder(order.id);
        NProgress.done();
    }

    async initSteam() {
        let {order} = this.props;
        let {payer_tradelink} = order;

        if (!payer_tradelink)
            alert('This order is unabled to be paid with Steam items');

        NProgress.start();
        await this.props.initSteamOrder(order.id);
        NProgress.set(0.5);
        await this.props.getOrder(order.id);
        NProgress.done();
    }

    steamEnabled() {
        let {order} = this.props;

        if (order.payer_tradelink)
            return 'hover:shadow hover:bg-white cursor-pointer ';

        return 'opacity-25 cursor-not-allowed ';
    }

    render() {
        let {selected} = this.state;

        return (
            <div className="flex flex-wrap p-4 justify-center items-stretch sm:p-6 sm:pt-20">
                <p className="mb-2 p-4 w-full text-center text-2xl text-grey-darkest">Escolha seu método de pagamento:</p>
                <div className="w-full sm:w-1/2 p-4 text-4xl">
                    <a onClick={this.init.bind(this, Processor.PAYPAL)} className="trans h-32 flex py-3 px-10 sm:px-5 justify-center items-center text-grey-darkest cursor-pointer rounded-lg bg-grey-lightest no-underline active:shadow-md hover:shadow hover:bg-white">
                        <Loader
                            loading={selected === Processor.PAYPAL}
                            url="https://logodownload.org/wp-content/uploads/2014/10/paypal-logo.png"
                        />
                    </a>
                </div>
                <div className="w-full sm:w-1/2 p-4 text-4xl">
                    <a onClick={this.init.bind(this, Processor.MERCADOPAGO)} className="trans h-32 flex py-3 px-10 sm:px-5 justify-center items-center text-grey-darkest cursor-pointer rounded-lg bg-grey-lightest no-underline active:shadow-md hover:shadow hover:bg-white">
                        <Loader
                            loading={selected === Processor.MERCADOPAGO}
                            url="http://www.freelogovectors.net/wp-content/uploads/2019/02/Mercadopago-logo.png"
                        />
                    </a>
                </div>
                <div className="w-full sm:w-1/2 p-4 text-4xl">
                    <a className="opacity-25 trans h-32 flex py-3 px-10 sm:px-5 justify-center items-center text-grey-darkest rounded-lg bg-grey-lightest cursor-not-allowed no-underline">
                        <Loader
                            loading={selected === Processor.PAGSEGURO}
                            url="https://cdn-images-1.medium.com/max/1200/1*NarjT54CL02HHKsSiw68zQ.png"
                        />
                    </a>
                </div>
                <div onClick={this.init.bind(this, Processor.STEAM)} className="w-full sm:w-1/2 p-4 text-4xl">
                    <a className={`${this.steamEnabled()}trans h-32 flex py-3 px-10 sm:px-5 justify-center items-center text-grey-darkest rounded-lg bg-grey-lightest no-underline`}>
                        <Loader
                            loading={selected === Processor.STEAM}
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
    setAccent: (color?) => dispatch(setAccent(color)),
    initMercadoPagoOrder: (orderId) => dispatch(initMercadoPagoOrder(orderId)),
    initPayPalOrder: (orderId) => dispatch(initPayPalOrder(orderId)),
    initSteamOrder: (orderId) => dispatch(initSteamOrder(orderId))
});

const mapStateToProps = state => ({});

export default withRouter(connect(
    mapStateToProps, mapDispatchToProps
)(PaymentMethodSelector));