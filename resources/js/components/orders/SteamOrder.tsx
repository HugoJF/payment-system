import React, {Component} from 'react';
import {Route, Switch, withRouter} from "react-router-dom";
import Inventory from "../ui/Inventory";
import OrderPending from "../order-components/OrderPending";
import OrderSuccess from "../order-components/OrderSuccess";
import OrderError from "../order-components/OrderError";
import {connect} from "react-redux";
import {getOrder} from "../../actions/Order";
import {getOrderType, Order, SteamOrder as SteamOrderType} from "../../constants/Types";

interface OwnProps {
    order: Order,
    action: string,
}

interface DispatchProps {
    getOrder: getOrderType
}

interface StateProps {
}

type Props = OwnProps & DispatchProps & StateProps;

class SteamOrder extends Component<Props> {
    timer?: any;

    constructor(props: Props) {
        super(props);

        this.checkTradeoffer = this.checkTradeoffer.bind(this);
    }

    componentDidMount() {
        this.timer = setInterval(this.checkTradeoffer, 5000);
    }

    componentWillUnmount() {
        this.clearTimer();
    }

    clearTimer() {
        clearInterval(this.timer);
    }

    checkTradeoffer() {
        if (this.props.order.orderable.tradeoffer_state === 3)
            this.clearTimer();

        this.props.getOrder(this.props.order.id);
    }

    render() {
        let {action, order} = this.props;
        let {orderable} = order;

        if (orderable.tradeoffer_state === 3) {
            return <OrderSuccess
                order={order}
            />
        }

        if (orderable.tradeoffer_state !== 2 && orderable.tradeoffer_state) {
            return <OrderError
                order={order}
            />
        }

        if (orderable.tradeoffer_state === 2 || action === 'pending') {
            return <OrderPending
                title={"AGUARDANDO CONFIRMAÇÃO DA TRADEOFFER"}
                order={order}
            />
        }

        return <Inventory
            order={order}
        />;
    }
}

const mapDispatchToProps = dispatch => ({
    getOrder: (id) => dispatch(getOrder(id)),
});

const mapStateToProps = state => ({});

export default withRouter(connect(
    mapStateToProps, mapDispatchToProps
)(SteamOrder));