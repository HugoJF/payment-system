import React, {Component} from 'react';

interface OwnProps {
    color: string,
}

interface DispatchProps {
}

interface StateProps {
}

type Props = OwnProps & DispatchProps & StateProps;

class Badge extends Component<Props> {
    static defaultProps = {
        color: 'blue',
    };

    render() {
        let {color} = this.props;

        return (
            <span className={`"uppercase mt-2 py-2 px-3 text-sm text-${color}-lightest font-bold bg-${color}-dark rounded-lg sm:mt-0`}>{this.props.children}</span>
        );
    }
}

export default Badge;