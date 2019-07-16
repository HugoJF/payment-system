import React, {Component} from 'react';
import {connect} from "react-redux";
import OrderSkeleton from "../order-components/OrderSkeleton";
import OrderSummary from "../order-components/OrderSummary";
import OrderSuccess from "../order-components/OrderSuccess";
import OrderError from "../order-components/OrderError";
import OrderPending from "../order-components/OrderPending";
import {executePayPalOrder, getOrder, initPayPalOrder} from "../../actions/Order";
import {executePayPalOrderType, getOrderType, initPayPalOrderType, Order, PayPalOrder as PayPalOrderType} from "../../constants/Types";
import NProgress from 'nprogress';

interface OwnProps {
    order: Order,
    action: string,
}

interface DispatchProps {
    executePayPalOrder: executePayPalOrderType,
    initPayPalOrder: initPayPalOrderType,
    getOrder: getOrderType
}

interface StateProps {
}

type Props = OwnProps & DispatchProps & StateProps;

class PayPalOrder extends Component<Props> {
    static defaultProps = {
        action: '',
    };

    async executeOrder(order) {
        NProgress.start();
        await this.props.executePayPalOrder(order.id);
        NProgress.set(0.5);
        await this.props.getOrder(order.id);
        NProgress.done();
    }

    render() {
        let {order, action} = this.props;
        let {orderable}: { orderable: PayPalOrderType } = order;

        // Returned from PayPal payment screen
        if (action === 'pending' && !orderable.status) {
            this.executeOrder(order);

            return <OrderPending
                order={order}
                title={"Por favor aguarde enquanto seu pagamento é processado"}
            />
        }

        // If missing status and PayPal link, it needs initialization
        if (!orderable.status && !orderable.paypal_link) {
            this.props.initPayPalOrder(order.id);

            return <OrderSkeleton/>;
        }

        // If PayPal link is present but no status, show redirect link
        if (!orderable.status && orderable.paypal_link)
            return <OrderSummary
                order={order}
                payUrl={orderable.paypal_link}
            />;

        // If order has a successful status
        if (orderable.status.toLowerCase() === 'completed' || orderable.status.toLowerCase() === 'processed')
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

export default connect(
    mapStateToProps, mapDispatchToProps
)(PayPalOrder);