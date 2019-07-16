import React, {Component} from 'react';
import {calculateUnits} from "../../constants/helpers";
import {SteamItemInformation} from "../../constants/Types";

interface OwnProps {
    item: SteamItemInformation,
    onModifySelection: (SteamItemInformation, boolean) => void
}

interface DispatchProps {
}

interface StateProps {
}

interface State {
    selected: boolean,
}

type Props = OwnProps & DispatchProps & StateProps;

class SteamItem extends Component<Props, State> {
    state = {
        selected: false,
    };

    constructor(props: Props) {
        super(props);

        this.onToggleSelect = this.onToggleSelect.bind(this);
    }

    onToggleSelect() {

        this.setState({
            selected: !this.state.selected
        }, () => {
            if (this.props.onModifySelection)
                this.props.onModifySelection(this.props.item, this.state.selected);
        })

    }

    render() {
        let {market_hash_name, price, icon_url} = this.props.item;
        let {selected} = this.state;

        return (
            <div className="w-full sm:w-1/2 md:w-1/3 p-4 text-4xl">
                <a onClick={this.onToggleSelect} className={`trans relative flex flex-col justify-center items-stretch text-grey-darkest rounded-lg cursor-pointer no-underline overflow-hidden ${selected ? 'bg-white shadow-md' : 'bg-transparent hover:shadow hover:bg-white'}`}>
                    <div style={{height: selected ? '0.75rem' : '0'}} className={`trans absolute pin-t ${selected ? 'shadow' : ''} w-full bg-blue-dark`}/>
                    <div className="py-3 px-10 sm:px-5">
                        <img className="max-w-full" src={icon_url}/>
                        <h3 className="text-sm font-normal">{market_hash_name}</h3>
                        <div className="mt-4 flex justify-start">
                            <span className="text-lg font-semibold">${(parseInt(price) / 100).toFixed(2)}</span>
                        </div>
                    </div>
                </a>
            </div>
        )
    }
}

export default SteamItem;