import React, {Component} from 'react';
import {connect} from "react-redux";
import OrderSummary from "../order-components/OrderSummary";
import OrderSuccess from "../order-components/OrderSuccess";
import OrderError from "../order-components/OrderError";
import OrderSkeleton from "../order-components/OrderSkeleton";
import OrderPending from "../order-components/OrderPending";
import {executeMpOrder, getOrder, initMercadoPagoOrder} from "../../actions/Order";
import {getOrderType, executeMpOrderType, initMercadoPagoOrderType, Order, Preference} from "../../constants/Types";
import NProgress from 'nprogress';

interface OwnProps {
    action: string,
}

interface DispatchProps {
    executeMpOrder: executeMpOrderType,
    initMercadoPagoOrder: initMercadoPagoOrderType,
    getOrder: getOrderType,
}

interface PreferencedOrder {
    preference: Preference,
}

interface StateProps {
    order: Order & PreferencedOrder,
}

type Props = OwnProps & DispatchProps & StateProps;

class MPOrder extends Component<Props> {
    async executeMpOrder(order) {
        NProgress.start();
        await this.props.executeMpOrder(order.id);
        NProgress.set(0.5);
        await this.props.getOrder(order.id);
        NProgress.done();
    }

    render() {
        let {order, action} = this.props;
        let {preference, paid} = {} = order;

        // Preference link will only exist after an order initialization (after payment method selection or when accessing the order directly)
        if (preference)
            return <OrderSummary
                order={order}
                payUrl={preference.init_point}
            />;

        // Check if order is already paid
        if (paid)
            return <OrderSuccess
                order={order}
            />;

        // MercadoPago client redirection after payment was successful or is waiting confirmation
        if (action === 'pending') {
            this.executeMpOrder(order);

            return <OrderPending
                order={order}
            />;
        }

        // MercadoPago client redirection after cancelling or payment failure
        if (action === 'failure')
            return <OrderError
                order={order}
                error={"CANCELADO"}
            />;

        this.props.initMercadoPagoOrder(order.id);

        return <OrderSkeleton/>
    }
}

const mapDispatchToProps = dispatch => ({
    executeMpOrder: (id) => dispatch(executeMpOrder(id)),
    getOrder: (id) => dispatch(getOrder(id)),
    initMercadoPagoOrder: (orderId) => dispatch(initMercadoPagoOrder(orderId)),
});

const mapStateToProps = (state, props) => ({});

export default connect(
    mapStateToProps, mapDispatchToProps
)(MPOrder);