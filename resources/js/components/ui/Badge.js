import React, {Component} from 'react';

class Badge extends Component {

    render() {
        let {color} = this.props;

        return (
            <span className={`"uppercase mt-2 py-2 px-3 text-sm text-${color}-lightest font-bold bg-${color}-dark rounded-lg sm:mt-0`}>{this.props.children}</span>
        );
    }
}

Badge.defaultProps = {
    color: 'blue',
};

export default Badge;