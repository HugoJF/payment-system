import React, {Component, Fragment} from 'react';
import SteamItem from "./SteamItem";
import {connect} from "react-redux";
import {setAccent, setAvatar, setWidth} from "../../actions/UI";
import {getInventory} from "../../actions/Inventory";
import InventoryHud from "./InventoryHud";
import {calculateUnits} from "../../constants/helpers";
import {executeSteamOrder} from "../../actions/Order";
import Dots from "./Dots";
import NProgress from 'nprogress';
import {redirect} from "../../constants/Routes";
import {withRouter} from "react-router-dom";
import sumBy from 'lodash.sumby';
import {executeSteamOrderType, getInventoryType, SteamItemInformation, Order, setAccentType, setAvatarType, setWidthType, SteamItemIdentification} from "../../constants/Types";

interface OwnProps {
    order: Order
    steamid: string,
}

interface DispatchProps {
    getInventory: getInventoryType,
    setAvatar: setAvatarType,
    setWidth: setWidthType,
    setAccent: setAccentType,
    executeSteamOrder: executeSteamOrderType
}

interface StateProps {
    steamid: string,
    inventory: any,
}

interface State {
    units: number,
    price: number,
    tradelink?: string,
    loading: boolean,
    error: boolean,
    executing: boolean,
}

type Props = OwnProps & DispatchProps & StateProps;

class Inventory extends Component<Props, State> {
    state = {
        units: 0,
        price: 0,
        tradelink: '',
        executing: false,
        error: false,
        loading: false,
    };

    selectedItems: { [key: string]: SteamItemIdentification };

    constructor(props: Props) {
        super(props);

        this.selectedItems = {};
        this.onExecuteSteamOrder = this.onExecuteSteamOrder.bind(this);
        this.redirectToPending = this.redirectToPending.bind(this);
        this.onModifySelection = this.onModifySelection.bind(this);
        this.getActionButton = this.getActionButton.bind(this);
        this.getInventory = this.getInventory.bind(this);
    }

    async getInventory() {
        this.setState({loading: true});
        try {
            await this.props.getInventory(this.props.steamid);
        } catch (e) {
            this.setState({error: true});
        } finally {
            this.setState({loading: false});
        }
    }

    componentWillMount() {
        this.getInventory();
        this.props.setAvatar(false);
        this.props.setWidth('w-1/2');
        this.props.setAccent('blue');
    }
    onModifySelection(item, selected) {
        let {unit_price, discount_per_unit, unit_price_limit} = this.props.order;

        // Modify list according to action
        if (selected) {
            this.selectedItems[item.assetid] = item;
        } else {
            delete this.selectedItems[item.assetid];
        }

        // Calculate total selected value
        let totalValue = sumBy(Object.values(this.selectedItems), 'price');

        // Calculate how many units can be bought with total value
        let units = calculateUnits(totalValue, unit_price, discount_per_unit, unit_price_limit);

        // Update state
        this.setState({
            units: units,
            price: totalValue / 100,
        })
    }

    async onExecuteSteamOrder() {
        let orderId = this.props.order.id;

        // Map only the essential information from each selected item
        let data = Object.values(this.selectedItems).map((item) => (
            {
                appid: item.appid,
                contextid: item.contextid,
                assetid: item.assetid,
            }
        ));

        // Set loading state
        NProgress.start();
        this.setState({
            executing: true,
        });

        // Execute order with item details to receive tradeoffer information
        let tradeOffer = await this.props.executeSteamOrder(orderId, data);
        NProgress.done();

        // Build tradeoffer link
        this.setState({
            executing: false,
            tradelink: `https://steamcommunity.com/tradeoffer/${tradeOffer.id}/`
        })
    }

    getActionButton(): JSX.Element {
        // Order is being executed and we are waiting for tradeoffer information
        if (this.state.executing)
            return (
                <a className="mt-8 py-4 px-12 w-full text-center bg-blue no-underline text-grey-lighter text-xl font-bold cursor-pointer rounded-lg shadow shadow-3d-blue-sm">
                    Aguardando trade offer
                    <Dots minDots={1} delay={750}/>
                </a>
            );

        // Tradelink is available
        if (this.state.tradelink)
            return (
                <a onClick={this.redirectToPending} href={this.state.tradelink} target="_blank" className="mt-8 py-4 px-12 w-full text-center bg-blue no-underline text-grey-lighter text-xl font-bold cursor-pointer rounded-lg shadow shadow-3d-blue-sm">
                    Abrir tradeoffer
                </a>
            );

        // Waiting for user to select items
        return (
            <a onClick={this.onExecuteSteamOrder} className="py-4 px-12 w-full text-center bg-blue no-underline text-grey-lighter text-xl font-bold cursor-pointer rounded-lg shadow shadow-3d-blue-sm hover:bg-blue-dark">Finalizar</a>
        )
    }

    redirectToPending() {
        redirect.bind(this)().steam().pending();
    }

    render() {
        let {order, inventory} = this.props;
        let {loading, error, price, units} = this.state;

        let items: SteamItemInformation[] = Object.values(inventory);

        return (
            <>
                <InventoryHud
                    order={order}
                    price={price}
                    units={units}
                    finalizeButton={this.getActionButton()}
                />

                <div className="flex flex-wrap justify-start items-stretch p-4">
                    {/* Inventory loaded */}
                    {
                        items.length !== 0 &&
                        <h3 className="mb-2 p-4 w-full text-center text-grey-darkest">Escolha as skin que você deseja utilizar na troca:</h3>
                    }

                    {/* Error loading inventory */}
                    {
                        error &&
                        <h2 className="py-10 w-full text-center font-semibold text-red tracking-wide uppercase">
                            Erro ao carregar inventário!
                        </h2>
                    }

                    {/* Loading inventory */}
                    {
                        loading &&
                        <h2 className="py-10 w-full text-center font-light text-grey-darkest tracking-wide uppercase">
                            Carregando inventário
                            <Dots minDots={1} delay={750}/>
                        </h2>
                    }

                    {/* Item list */}
                    {
                        items.map((item) => (
                            <SteamItem
                                key={item.assetid}
                                onModifySelection={this.onModifySelection}
                                item={item}
                            />
                        ))
                    }

                    {/* Send tradeoffer button */}
                    {this.getActionButton()}

                </div>
            </>
        );
    }
}

const mapDispatchToProps = dispatch => ({
    executeSteamOrder: (id, data) => dispatch(executeSteamOrder(id, data)),
    getInventory: (steamid) => dispatch(getInventory(steamid)),
    setAccent: (color?) => dispatch(setAccent(color)),
    setAvatar: (visible, url) => dispatch(setAvatar(visible, url)),
    setWidth: (width) => dispatch(setWidth(width))
});

const mapStateToProps = (state, props) => ({
    inventory: state.inventory,
    steamid: props.order.payer_steam_id,
});

export default withRouter(connect(
    mapStateToProps, mapDispatchToProps
)(Inventory));