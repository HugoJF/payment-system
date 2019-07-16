import React, {Component} from 'react';
import {connect} from "react-redux";
import Avatar from "./Avatar";

interface OwnProps {
}

interface DispatchProps {
}

interface StateProps {
    color: string,
    avatar: boolean,
    width: string,
    url: string,
}

type Props = OwnProps & DispatchProps & StateProps;

class Container extends Component<Props> {
    render() {
        let {color, avatar, url, width} = this.props;

        return (
            <>
                {
                    avatar && <Avatar url={url}/>
                }
                <div
                    className={`flex flex-col m-auto lg:w-1/2 xl:${width} w-full justify-center bg-grey-lightest border-0 border-${color}-dark rounded-lg shadow-lg overflow-hidden`}
                >
                    {this.props.children}

                    <div className="h-4 w-full">
                        <div className={`trans h-full w-full bg-${color}-dark w-64`}/>
                    </div>
                </div>
            </>
        );
    }
}

const mapDispatchToProps = dispatch => ({});

const mapStateToProps = state => ({
    color: state.ui.color,
    avatar: state.ui.avatar,
    width: state.ui.width,
    url: state.ui.url,
});

export default connect(
    mapStateToProps, mapDispatchToProps
)(Container);