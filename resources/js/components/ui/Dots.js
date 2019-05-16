import React, {Component} from 'react';
import PropTypes from 'prop-types';

class Dots extends Component {
    state = {
        dots: 0,
    };

    componentDidMount() {
        this.timer = setInterval(this.dots.bind(this), this.props.delay);
    }

    componentWillUnmount() {
        clearInterval(this.timer);
    }

    dots() {
        let dotCount = this.state.dots + 1;

        dotCount = dotCount > this.props.maxDots ? this.props.minDots : dotCount;

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

Dots.propTypes = {
    delay: PropTypes.number,
    maxDots: PropTypes.number,
    minDots: PropTypes.number,
    width: PropTypes.number,
};

Dots.defaultProps = {
    delay: 1000,
    maxDots: 3,
    minDots: 0,
    width: 10,
};

export default Dots;