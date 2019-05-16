import React, {Component, Fragment} from 'react';
import {connect} from "react-redux";
import Avatar from "./Avatar";

class Container extends Component {

    render() {
        let {color, avatar, width} = this.props;

        return (
            <Fragment>
                {
                    avatar && <Avatar/>
                }
                <div
                    className={`flex flex-col m-auto lg:w-1/2 xl:${width} w-full justify-center bg-grey-lightest border border-${color}-dark rounded-lg shadow-lg overflow-hidden`}
                >
                    {this.props.children}

                    <div className="h-4 w-full">
                        <div className={`trans h-full w-full bg-${color}-dark w-64`}/>
                    </div>
                </div>
            </Fragment>
        );
    }
}

const mapDispatchToProps = dispatch => ({});

const mapStateToProps = state => ({
    color: state.ui.color,
    avatar: state.ui.avatar,
    width: state.ui.width,
});

export default connect(
    mapStateToProps, mapDispatchToProps
)(Container);