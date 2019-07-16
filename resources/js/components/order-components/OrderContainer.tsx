import React, {Component} from 'react';
import {connect} from "react-redux";
import {getOrder} from "../../actions/Order";
import PaymentMethodSelector from "../PaymentMethodSelector";
import OrderSkeleton from "./OrderSkeleton";
import MPOrder from "../orders/MPOrder";
import PayPalOrder from "../orders/PayPalOrder";
import SteamOrder from "../orders/SteamOrder";
import {getOrderType, Order, setAvatarType} from "../../constants/Types";
import {setAvatar} from "../../actions/UI";

interface OwnProps {
    orderId: string
    action?: string,
}

interface DispatchProps {
    getOrder: getOrderType,
    setAvatar: setAvatarType
}

interface StateProps {
    order?: Order
}

type Props = OwnProps & DispatchProps & StateProps;

class OrderContainer extends Component<Props> {
    componentWillMount() {
        this.boot();
    }

    async boot() {
        let {orderId, order} = this.props;

        if (!order){
            let order = await this.props.getOrder(orderId);
            this.props.setAvatar(true, order.avatar)
        }
    }

    render() {
        let {order, action} = this.props;

        // Retrieve order
        if (!order) return <OrderSkeleton/>;


        // Generic order that is not initialized
        if (!order.orderable_id || !order.orderable_type) {
            return <PaymentMethodSelector
                order={order}
            />
        }

        // Map order types to components
        let orderViews = {
            'App\\MPOrder': MPOrder,
            'App\\PayPalOrder': PayPalOrder,
            'App\\SteamOrder': SteamOrder,
        };

        // Select correct view for this order
        let OrderView = orderViews[order.orderable_type];

        // Render it
        return <OrderView
            order={order}
            action={action}
        />
    }
}

const mapDispatchToProps = dispatch => ({
    getOrder: (id) => dispatch(getOrder(id)),
    setAvatar: (visible, url) => dispatch(setAvatar(visible, url)),
});

const mapStateToProps = (state, props) => ({
    order: state.orders[props.orderId],
});

export default connect(
    mapStateToProps, mapDispatchToProps
)(OrderContainer);