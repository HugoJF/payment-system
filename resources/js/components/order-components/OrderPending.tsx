import React, {Component} from 'react';
import {connect} from "react-redux";
import {setAccent} from "../../actions/UI";
import OrderState from "./OrderState";
import PropTypes from 'prop-types';
import Loading from "../svg/Loading";
import {Order, setAccentType} from "../../constants/Types";

interface OwnProps {
    order: Order,
    title?: string,
    error?: string,
}

interface DispatchProps {
    setAccent: setAccentType
}

interface StateProps {
    order: Order
}

type Props = OwnProps & DispatchProps & StateProps;

class OrderPending extends Component<Props> {
    static defaultProps = {
        title: 'Aguardando',
    };

    componentWillMount() {
        this.props.setAccent('yellow');
    }

    render() {
        let {order, title} = this.props;

        return (
            <div className="flex flex-col p-4 justify-center items-center sm:p-6">
                <OrderState
                    id={order.id}
                    state="PENDENTE"
                    color="yellow"
                />

                <h2 className="mt-16 uppercase text-grey-dark text-center text-2xl font-normal tracking-wide">{title}</h2>

                <div className="text-grey pt-4">
                    <Loading size="3rem"/>
                </div>
            </div>
        );
    }
}

const mapDispatchToProps = dispatch => ({
    setAccent: (color?) => dispatch(setAccent(color))
});

const mapStateToProps = state => ({});

export default connect(
    mapStateToProps, mapDispatchToProps
)(OrderPending);