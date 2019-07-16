import React, {Component} from 'react';
import Badge from "../ui/Badge";

interface Props {
    id: string,
    color: string,
    state?: string,
}

class OrderState extends Component<Props> {
    static defaultProps = {
        color: 'blue',
        state: 'Aguardando',
    };

    render() {
        return (
            <div className="flex flex-col self-stretch items-center justify-between sm:flex-row sm:items-start">
                <h2 className="text-grey-dark text-lg font-mono font-medium"><strong>#</strong>{this.props.id}</h2>
                <Badge color={this.props.color}>{this.props.state}</Badge>
            </div>
        );
    }
}

export default OrderState;