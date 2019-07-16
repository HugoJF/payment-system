import React, {Component} from 'react';

interface OwnProps {
    url: string,
}

interface DispatchProps {
}

interface StateProps {
}

type Props = OwnProps & DispatchProps & StateProps;

class Avatar extends Component<Props> {
    render() {
        return (
            <div className="relative flex justify-center w-full m-auto">
                <div className="absolute hidden -translate-50 self-center pin-t p-4 justify-center items-center bg-white rounded-full shadow sm:flex">
                    {
                        this.props.url
                            ?
                            <img className="h-32 w-32 rounded-full" src={this.props.url}/>
                            :
                            <div className="h-32 w-32"/>
                    }
                </div>
            </div>
        );
    }
}

export default Avatar;