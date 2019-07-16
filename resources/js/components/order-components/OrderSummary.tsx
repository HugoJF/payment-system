import React, {Component} from 'react';
import Button from "../ui/Button";
import OrderState from "./OrderState";
import {connect} from "react-redux";
import {setAccent} from "../../actions/UI";
import {Order, setAccentType} from "../../constants/Types";

interface OwnProps {
    order: Order
    payUrl: string,
    className?: string,
}

interface DispatchProps {
    setAccent: setAccentType
}

interface StateProps {
    order: Order
}

interface State {
    loading: boolean,
}

type Props = OwnProps & DispatchProps & StateProps;

class OrderSummary extends Component<Props, State> {
    static defaultProps = {
        payUrl: '#',
    };

    state = {
        loading: false,
    };

    constructor(props: Props) {
        super(props);

        this.onPay = this.onPay.bind(this);
    }

    componentWillMount() {
        this.props.setAccent('blue');
    }

    onPay() {
        this.setState({loading: true});
    }

    render() {
        let {order, payUrl} = this.props;
        let {loading} = this.state;

        return (
            <div className="flex flex-col p-4 justify-center items-center sm:p-6">
                <OrderState
                    id={order.id}
                    state="AGUARDANDO"
                    color="blue"
                />
                <p className="mt-12 text-grey-dark text-sm">{order.reason}</p>

                <h2 className="mt-12 uppercase text-grey-dark text-center text-2xl font-normal tracking-wide">Valor total</h2>
                <p className="flex mt-8 pb-4 justify-center items-baseline text-center text-5xl">
                    <span className="mr-1 text-3xl text-grey font-normal">R$</span>
                    <span className="text-grey-darkest font-semibold">{(order.preset_amount / 100).toFixed(2)}</span>
                </p>

                <Button loading={loading} onClick={this.onPay} href={payUrl}>Pagar</Button>
            </div>
        );
    }
}

const mapDispatchToProps = dispatch => ({
    setAccent: (color?) => dispatch(setAccent(color)),
});

const mapStateToProps = state => ({});

export default connect(
    mapStateToProps, mapDispatchToProps
)(OrderSummary);