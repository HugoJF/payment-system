import React, {Component, Fragment} from 'react';
import {connect} from "react-redux";
import {setAccent} from "../actions/UI";
import {Order, setAccentType} from "../constants/Types";

interface OwnProps {
}

interface DispatchProps {
    setAccent: setAccentType,
}

interface StateProps {
    order: Order,
}

type Props = OwnProps & DispatchProps & StateProps;

class PendingTradeoffer extends Component<Props> {
    componentWillMount() {
        this.props.setAccent('yellow');
    }

    render() {
        let {order} = this.props;

        return (
            <div className="flex flex-col p-4 justify-center items-center sm:p-6">
                <div className="flex flex-col self-stretch items-center justify-between sm:flex-row sm:items-start">
                    <h2 className="text-grey-dark text-lg font-mono font-medium">#{order.id}</h2>
                    <span className="uppercase mt-2 py-2 px-3 text-sm text-yellow-darkest font-bold bg-yellow-dark rounded-lg sm:mt-0">Pendente</span>
                </div>

                <h2 className="mt-16 uppercase text-grey-dark text-center text-2xl font-normal tracking-wide">Aguardando tradeoffer</h2>

                <img className="mt-4 w-20 h-20" src="https://www.advisory.com/assets/responsive/images/loading1.gif"/>
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
)(PendingTradeoffer);