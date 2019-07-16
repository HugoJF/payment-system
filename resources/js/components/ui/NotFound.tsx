import React, {Component} from 'react';
import {setAccent} from "../../actions/UI";
import {connect} from "react-redux";
import {setAccentType} from "../../constants/Types";

interface OwnProps {
}

interface DispatchProps {
    setAccent: setAccentType,
}

interface StateProps {
}

type Props = OwnProps & DispatchProps & StateProps;

class NotFound extends Component<Props> {
    componentWillMount() {
        this.props.setAccent('grey');
    }
    render() {
        return (
            <div className="flex flex-col mt-12 h-64 p-4 justify-center items-center sm:p-6">
                <h1 className="font-thin text-grey-darkest text-8xl tracking-wider">404</h1>
                <p className="font-hairline text-grey-darker text-4xl">Not Found</p>
            </div>
        );
    }
}

const mapDispatchToProps = dispatch => ({
    setAccent: (color?) => dispatch(setAccent(color))
});

const mapStateToProps = state => ({});

export default connect(
    mapStateToProps, mapDispatchToProps
)(NotFound);