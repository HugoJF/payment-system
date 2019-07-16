import React, {Component} from 'react';

interface OwnProps {
    size: string,
}

type Props = OwnProps;

class Loading extends Component<Props> {
    static defaultProps = {
        size: ''
    };

    render() {
        let {size} = this.props;

        return (
            <div style={{width: size, height: size}} className="spinner-border"/>
        );
    }
}

export default Loading;