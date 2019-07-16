import React, {Component} from 'react';
import Cross from "../svg/Cross";
import {connect} from "react-redux";
import {setAccent} from "../../actions/UI";
import {withRouter} from "react-router-dom";
import OrderState from "./OrderState";
import {Order, setAccentType as setAccentType} from "../../constants/Types";

interface OwnProps {
    order: Order,
    error?: string,
}

interface DispatchProps {
    setAccent: setAccentType
}

interface StateProps {
    order: Order
}

type Props = OwnProps & DispatchProps & StateProps;

class OrderError extends Component<Props> {
    static defaultProps = {
        error: 'RECUSADO',
    };

    componentWillMount() {
        this.props.setAccent('red');
    }
    render() {
        let {order} = this.props;

        return (
            <div className="flex flex-col p-4 justify-center items-center sm:p-6">
                <OrderState
                    id={order.id}
                    color="red"
                    state={this.props.error}
                />

                <h2 className="mt-16 uppercase text-grey-dark text-center text-2xl font-normal tracking-wide">Valor total</h2>

                <p className="flex mt-8 pb-4 justify-center items-baseline text-center text-5xl">
                    <span className="mr-1 text-3xl text-grey font-normal">R$</span>
                    <span className="text-grey-darkest font-semibold">{(order.preset_amount / 100).toFixed(2)}</span>
                </p>

                <Cross className="my-2 w-16 h-16 fill-current text-red-dark"/>

                <a href={order.return_url} className="mt-4 py-4 px-12 text-grey-darker text-xl font-normal no-underline hover:text-grey-darkest hover:underline">‹ Voltar ao VIP-Admin</a>
            </div>
        );
    }
}

const mapDispatchToProps = dispatch => ({
    setAccent: (color?) => dispatch(setAccent(color))
});

const mapStateToProps = state => ({});

export default withRouter(connect(
    mapStateToProps, mapDispatchToProps
)(OrderError));