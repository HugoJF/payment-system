import React, {Component} from 'react';
import PropTypes from 'prop-types';
import {Order, setAccentType} from "../../constants/Types";

interface OwnProps {
    delay?: number,
    maxDots?: number,
    minDots?: number,
    width?: number,
}

interface DispatchProps {}

interface StateProps {}

type Props = OwnProps & DispatchProps & StateProps;

class Dots extends Component<Props> {
    static defaultProps = {
        delay: 1000,
        maxDots: 3,
        minDots: 0,
        width: 10,
    };

    state = {
        dots: 0,
    };

    timer?: any;

    componentDidMount() {
        this.timer = setInterval(this.dots.bind(this), this.props.delay);
    }

    componentWillUnmount() {
        clearInterval(this.timer);
    }

    dots() {
        let dotCount = this.state.dots + 1;

        dotCount = dotCount > (this.props.maxDots as number) ? (this.props.minDots as number) : dotCount;

        this.setState({
            dots: dotCount,
        })
    }

    render() {
        return (
            <span
                style={{
                    display: 'inline-block',
                    textAlign: 'left',
                    width: `${this.props.width}px`,
                }}
            >{'.'.repeat(this.state.dots || 0)}</span>
        );
    }
}

export default Dots;