import React, {Component} from 'react';
import {connect} from "react-redux";
import {setAccent} from "../../actions/UI";
import OrderState from "./OrderState";
import PropTypes from 'prop-types';

class OrderSuccess extends Component {
    componentWillMount() {
        this.props.setAccent('green');
    }

    render() {
        let order = this.props.order;

        return (
            <div className="flex flex-col p-4 justify-center items-center sm:p-6">
                <OrderState
                    id={order.public_id}
                    state="PAGO"
                    color="green"
                />

                <h2 className="mt-16 uppercase text-grey-dark text-center text-2xl font-normal tracking-wide">Valor total</h2>
                <p className="flex mt-8 mb-4 justify-center items-baseline text-center text-5xl">
                    <span className="mr-1 text-3xl text-grey font-normal">R$</span>
                    <span className="text-grey-darkest font-semibold">{(order.preset_amount / 100).toFixed(2)}</span>
                </p>

                <svg className="my-2 w-16 h-16 fill-current text-green-dark" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 426.667 426.667">
                    <path d="M213.333,0C95.518,0,0,95.514,0,213.333s95.518,213.333,213.333,213.333c117.828,0,213.333-95.514,213.333-213.333S331.157,0,213.333,0z M174.199,322.918l-93.935-93.931l31.309-31.309l62.626,62.622l140.894-140.898l31.309,31.309L174.199,322.918z"/>
                </svg>

                <a href={order.return_url} className="py-4 px-12 text-grey-darker text-xl font-normal no-underline hover:text-grey-darkest hover:underline">‹ Voltar ao VIP-Admin</a>
            </div>
        );
    }
}

const mapDispatchToProps = dispatch => ({
    setAccent: (color) => dispatch(setAccent(color))
});

const mapStateToProps = state => ({});

OrderSuccess.propTypes = {
    order: PropTypes.object.isRequired,
};

export default connect(
    mapStateToProps, mapDispatchToProps
)(OrderSuccess);