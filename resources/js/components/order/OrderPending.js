import React, {Component} from 'react';
import {connect} from "react-redux";
import {setAccent} from "../../actions/UI";
import OrderState from "./OrderState";
import PropTypes from 'prop-types';

class OrderPending extends Component {
    componentWillMount() {
        this.props.setAccent('yellow');
    }

    render() {
        let {order, title} = this.props;

        return (
            <div className="flex flex-col p-4 justify-center items-center sm:p-6">
                <OrderState
                    id={order.public_id}
                    state="PENDENTE"
                    color="yellow"
                />

                <h2 className="mt-16 uppercase text-grey-dark text-center text-2xl font-normal tracking-wide">{title}</h2>

                <img className="mt-4 w-20 h-20" src="https://www.advisory.com/assets/responsive/images/loading1.gif"/>
            </div>
        );
    }
}

const mapDispatchToProps = dispatch => ({
    setAccent: (color) => dispatch(setAccent(color))
});

const mapStateToProps = state => ({});

OrderPending.propTypes = {
    order: PropTypes.object.isRequired,
};

OrderPending.defaultProps = {
    title: 'Aguardando',
};

export default connect(
    mapStateToProps, mapDispatchToProps
)(OrderPending);