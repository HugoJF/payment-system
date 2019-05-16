import React, {Component} from 'react';
import OrderSummary from "./OrderSummary";
import OrderSuccess from "./OrderSuccess";
import OrderError from "./OrderError";
import {connect} from "react-redux";
import {executeMpOrder, getOrder, initMercadoPagoOrder} from "../../actions/Order";
import OrderSkeleton from "./OrderSkeleton";
import PropTypes from 'prop-types';
import OrderPending from "./OrderPending";
import NProgress from 'nprogress';

class MPOrder extends Component {
    render() {
        let {order, preference, action} = this.props;

        if (preference)
            return <OrderSummary
                order={order}
                payUrl={preference.init_point}
            />;

        if (order.mp_paid_amount >= order.preset_amount)
            return <OrderSuccess
                order={order}
            />;

        if (action === 'pending') {
            NProgress.start();
            this.props.executeMpOrder(order.public_id)
                .then(() => {
                    NProgress.set(0.5);
                    this.props.getOrder(order.public_id).then(() => {
                        NProgress.done();
                    });
                });
            return <OrderPending
                order={order}
            />;
        }

        if (action === 'failure')
            return <OrderError
                order={order}
                error={"CANCELADO"}
            />;

        this.props.initMercadoPagoOrder(order.public_id);

        return <OrderSkeleton/>
    }
}

const mapDispatchToProps = dispatch => ({
    executeMpOrder: (id) => dispatch(executeMpOrder(id)),
    getOrder: (id) => dispatch(getOrder(id)),
    initMercadoPagoOrder: (orderId) => dispatch(initMercadoPagoOrder(orderId)),
});

const mapStateToProps = (state, props) => ({
    preference: state.preferences[props.order.public_id],
});

MPOrder.propTypes = {
    order: PropTypes.object.isRequired,
    preference: PropTypes.object,
    action: PropTypes.string,
};

export default connect(
    mapStateToProps, mapDispatchToProps
)(MPOrder);