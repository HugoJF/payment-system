import React, {Component} from 'react';

interface OwnProps {
    href?: string,
    loading?: boolean,
    children: any,
    onClick: any,
}

interface DispatchProps {
}

interface StateProps {
}

type Props = OwnProps & DispatchProps & StateProps;

class Button extends Component<Props> {
    static defaultProps = {
        loading: false,
    };

    render() {
        let {loading, onClick, ...rest} = this.props;

        let loadingCss = loading ? 'pointer-events-none ' : '';

        return (
            <a {...rest} onClick={onClick} className={`mt-4 py-4 px-12 bg-blue ${loadingCss}no-underline text-grey-lighter text-xl font-bold rounded-lg cursor-pointer shadow shadow-3d-blue-sm hover:bg-blue-dark`}>
                {loading && <div className="-ml-3 mr-3 spinner-border"/>}
                <span>{this.props.children}</span>
            </a>
        );
    }
}

export default Button;