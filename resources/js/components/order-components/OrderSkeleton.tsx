import React, {Component} from 'react';
import {connect} from "react-redux";
import {setAccent} from '../../actions/UI';
import {setAccentType} from "../../constants/Types";

interface OwnProps {
}

interface DispatchProps {
    setAccent: setAccentType
}

interface StateProps {
}

type Props = OwnProps & DispatchProps & StateProps;

class OrderSkeleton extends Component<Props> {
    componentWillMount() {
        this.props.setAccent('grey');
    }

    render() {
        return (
            <div className="flex flex-col p-4 justify-center items-center sm:p-6">
                <div className="flex flex-col self-stretch items-center justify-between sm:flex-row sm:items-start">
                    <h2 className="h-5 w-28 bg-grey-light rounded-lg"/>
                    <span className="py-2 px-3 h-8 w-32 bg-grey-light rounded-lg"/>
                </div>
                <p className="mt-12 h-4 w-64 bg-grey-light rounded-lg text-sm"/>

                <h2 className="mt-12 h-8 w-48 bg-grey-light rounded-lg"/>
                <p className="flex mt-8 pb-4 h-12 w-32 bg-grey-light rounded-lg"/>

                <a href="#" className="mt-4 h-12 w-48 py-14 px-12 bg-grey-light rounded-lg"/>
            </div>
        );
    }
}

const mapDispatchToProps = dispatch => ({
    setAccent: (color) => dispatch(setAccent(color))
});

const mapStateToProps = state => ({});

export default connect(
    mapStateToProps, mapDispatchToProps
)(OrderSkeleton);