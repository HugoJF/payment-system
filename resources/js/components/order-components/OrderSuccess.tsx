import React, {Component} from 'react';
import {connect} from "react-redux";
import {setAccent} from "../../actions/UI";
import OrderState from "./OrderState";
import Check from "../svg/Check";
import {Order, setAccentType} from "../../constants/Types";

interface OwnProps {
    order: Order,
}

interface DispatchProps {
    setAccent: setAccentType,
}

interface StateProps {
}

type Props = OwnProps & DispatchProps & StateProps;

class OrderSuccess extends Component<Props> {
    componentWillMount() {
        this.props.setAccent('green');
    }
    render() {
        let {id, preset_amount, return_url} = this.props.order;

        return (
            <div className="flex flex-col p-4 justify-center items-center sm:p-6">
                <OrderState
                    id={id}
                    state="PAGO"
                    color="green"
                />

                <h2 className="mt-16 uppercase text-grey-dark text-center text-2xl font-normal tracking-wide">Valor total</h2>
                <p className="flex mt-8 mb-4 justify-center items-baseline text-center text-5xl">
                    <span className="mr-1 text-3xl text-grey font-normal">R$</span>
                    <span className="text-grey-darkest font-semibold">{(preset_amount / 100).toFixed(2)}</span>
                </p>

                <Check/>

                <a href={return_url} className="py-4 px-12 text-grey-darker text-xl font-normal no-underline hover:text-grey-darkest hover:underline">‹ Voltar</a>
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
)(OrderSuccess);