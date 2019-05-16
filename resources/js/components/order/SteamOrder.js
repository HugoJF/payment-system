import React, {Component} from 'react';
import {Route, Switch, withRouter} from "react-router-dom";
import Inventory from "../ui/Inventory";
import OrderPending from "./OrderPending";
import OrderSuccess from "./OrderSuccess";
import OrderError from "./OrderError";
import {connect} from "react-redux";
import {getOrder} from "../../actions/Order";

class SteamOrder extends Component {
    constructor() {
        super();
        
        this.checkTradeoffer = this.checkTradeoffer.bind(this);
    }

    componentDidMount() {
        this.timer = setInterval(this.checkTradeoffer, 5000);
    }

    componentWillUnmount() {
        clearInterval(this.timer);
    }

    checkTradeoffer() {
        this.props.getOrder(this.props.order.public_id);
    }

    render() {
        let {action, order} = this.props;

        if (order.tradeoffer_state === 3) {
            return <OrderSuccess
                order={order}
            />
        }

        if (order.tradeoffer_state !== 2 && order.tradeoffer_state) {
            return <OrderError
                order={order}
            />
        }

        if (order.tradeoffer_state === 2 || action === 'pending') {
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