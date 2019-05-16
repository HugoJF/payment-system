import React, {Component} from 'react';

class Button extends Component {
    render() {
        return (
            <a {...this.props} className="mt-4 py-4 px-12 bg-blue no-underline text-grey-lighter text-xl font-bold rounded-lg cursor-pointer shadow shadow-3d-blue-sm hover:bg-blue-dark">
                {this.props.children}
            </a>
        );
    }
}

export default Button;