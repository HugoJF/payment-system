import React, {Component} from 'react';
import Button from "../ui/Button";
import OrderState from "./OrderState";
import {connect} from "react-redux";
import {setAccent} from "../../actions/UI";
import PropTypes from 'prop-types';

class OrderSummary extends Component {
    componentDidUpdate() {
        console.log('OrderSummary.js');
    }

    render() {
        let {order, payUrl} = this.props;

        return (
            <div className="flex flex-col p-4 justify-center items-center sm:p-6">
                <OrderState
                    id={order.public_id}
                    state="AGUARDANDO"
                    color="blue"
                />
                <p className="mt-12 text-grey-dark text-sm">{order.reason}</p>

                <h2 className="mt-12 uppercase text-grey-dark text-center text-2xl font-normal tracking-wide">Valor total</h2>
                <p className="flex mt-8 pb-4 justify-center items-baseline text-center text-5xl">
                    <span className="mr-1 text-3xl text-grey font-normal">R$</span>
                    <span className="text-grey-darkest font-semibold">{(order.preset_amount / 100).toFixed(2)}</span>
                </p>

                <Button href={payUrl}>Pagar</Button>
            </div>
        );
    }
}

const mapDispatchToProps = dispatch => ({
    setAccent: (accent) => dispatch(setAccent(accent)),
});

const mapStateToProps = state => ({
    ui: state.ui,
});

OrderSummary.propTypes = {
    order: PropTypes.object.isRequired,
    payUrl: PropTypes.string,
};

OrderSummary.defaultProps = {
    payUrl: '#',
};

export default connect(
    mapStateToProps, mapDispatchToProps
)(OrderSummary);