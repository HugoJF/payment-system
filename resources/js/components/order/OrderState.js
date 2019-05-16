import React, {Component} from 'react';
import Badge from "../ui/Badge";
import PropTypes from 'prop-types';

class OrderState extends Component {
    render() {
        return (
            <div className="flex flex-col self-stretch items-center justify-between sm:flex-row sm:items-start">
                <h2 className="text-grey-dark text-lg font-mono font-medium"><strong>#</strong>{this.props.id}</h2>
                <Badge color={this.props.color}>{this.props.state}</Badge>
            </div>
        );
    }
}

OrderState.propTypes = {
    id: PropTypes.string.isRequired,
    color: PropTypes.string,
    state: PropTypes.string,
};

OrderState.defaultProps = {
    color: 'blue',
    state: 'Aguardando',
};

export default OrderState;