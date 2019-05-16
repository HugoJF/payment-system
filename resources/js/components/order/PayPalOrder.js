import React, {Component} from 'react';
import PropTypes from 'prop-types';
import OrderSkeleton from "./OrderSkeleton";
import OrderSummary from "./OrderSummary";
import OrderSuccess from "./OrderSuccess";
import OrderError from "./OrderError";
import {connect} from "react-redux";
import {executePayPalOrder, getOrder, initPayPalOrder} from "../../actions/Order";
import OrderPending from "./OrderPending";
import NProgress from 'nprogress';

class PayPalOrder extends Component {
    getOrder(order) {
        this.props.getOrder(order.public_id)
            .then(() => {
                NProgress.done();
            });
    }

    executeOrder(order) {
        NProgress.start();

        this.props.executePayPalOrder(order.public_id)
            .then(() => {
                NProgress.set(0.5);
                this.getOrder(order);
            });
    }

    render() {
        let {order, action} = this.props;

        // Returned from PayPal payment screen
        if (action === 'pending' && !order.status) {
            this.executeOrder(order);

            return <OrderPending
                order={order}
                title={"Por favor aguarde enquanto seu pagamento é processado"}
            />
        }

        // If missing status and PayPal link, it needs initialization
        if (!order.status && !order.paypal_link) {
            this.props.initPayPalOrder(order.public_id);

            return <OrderSkeleton/>;
        }

        // If PayPal link is present but no status, show redirect link
        if (!order.status && order.paypal_link)
            return <OrderSummary
                order={order}
                payUrl={order.paypal_link}
            />;

        // If order has a successful status
        if (order.status.toLowerCase() === 'completed' || order.status.toLowerCase() === 'processed')
            return <OrderSuccess
                order={order}
            />;

        // If order status is not valid, something wrong happened
        return <OrderError
            order={order}
        />
    }
}

const mapDispatchToProps = dispatch => ({
    getOrder: (id) => dispatch(getOrder(id)),
    executePayPalOrder: (id) => dispatch(executePayPalOrder(id)),
    initPayPalOrder: (orderId) => dispatch(initPayPalOrder(orderId))
});

const mapStateToProps = state => ({});

PayPalOrder.defaultProps = {
    action: '',
};

PayPalOrder.propTypes = {
    order: PropTypes.object.isRequired,
    action: PropTypes.string,
};

export default connect(
    mapStateToProps, mapDispatchToProps
)(PayPalOrder);