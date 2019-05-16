import React, {Component} from 'react';
import {connect} from "react-redux";
import {getOrder} from "../../actions/Order";
import PaymentMethodSelector from "../PaymentMethodSelector";
import OrderSkeleton from "./OrderSkeleton";
import MPOrder from "./MPOrder";
import PropTypes from 'prop-types';
import PayPalOrder from "./PayPalOrder";
import SteamOrder from "./SteamOrder";
import {Route, Router} from "react-router-dom";

class Order extends Component {
    componentDidUpdate() {
        console.log('Order.js updated');
    }

    componentWillMount() {
        if (!this.props.order)
            this.props.getOrder(this.props.orderId)
    }

    render() {
        // Retrieve order
        if (!this.props.order) return <OrderSkeleton/>;

        let {order, action} = this.props;

        // Generic order
        if (!order.orderable_id || !order.orderable_type) {
            return <PaymentMethodSelector
                order={order}
            />
        }

        // MercadoPago order
        if (order.orderable_type === 'App\\MPOrder') {
            return <MPOrder
                order={order}
                action={action}
            />
        }

        // PayPal order
        if (order.orderable_type === 'App\\PayPalOrder') {
            return <PayPalOrder
                order={order}
                action={action}
            />
        }

        if (order.orderable_type === 'App\\SteamOrder') {
            return <SteamOrder
                order={order}
                action={action}
            />
        }
    }
}

const mapDispatchToProps = dispatch => ({
    getOrder: (id) => dispatch(getOrder(id))
});

const mapStateToProps = (state, props) => ({
    order: state.orders[props.orderId],
});

Order.propTypes = {
    orderId: PropTypes.string.isRequired,
};

export default connect(
    mapStateToProps, mapDispatchToProps
)(Order);