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

class Inventory extends Component {
    state = {
        units: 0,
        price: 0,
        executing: false,
    };

    constructor() {
        super();

        this.selectedItems = {};
        this.onExecuteSteamOrder = this.onExecuteSteamOrder.bind(this);
        this.redirectToPending = this.redirectToPending.bind(this);
    }

    componentWillMount() {
        this.props.getInventory(this.props.steamid);
        this.props.setAvatar(false);
        this.props.setWidth('w-1/2');
        this.props.setAccent('blue');
    }

    onModifySelection(item, selected) {
        let {unit_price, discount_per_unit, unit_price_limit} = this.props.order;

        if (selected) {
            this.selectedItems[item.assetid] = item;
        } else {
            delete this.selectedItems[item.assetid];
        }

        let totalValue = Object.values(this.selectedItems).reduce((price, i) => (
            price + i.price
        ), 0);

        let units = calculateUnits(totalValue, unit_price, discount_per_unit, unit_price_limit);

        this.setState({
            units: units,
            price: totalValue / 100,
        })
    }

    onExecuteSteamOrder() {
        let orderId = this.props.order.public_id;

        let data = Object.values(this.selectedItems).map((item) => (
            {
                appid: item.appid,
                contextid: item.contextid,
                assetid: item.assetid,
            }
        ));

        NProgress.start();
        this.setState({
            executing: true,
        });

        this.props.executeSteamOrder(orderId, data).then((d) => {
            NProgress.done();
            // check if state is 2
            this.setState({
                executing: false,
                tradelink: `https://steamcommunity.com/tradeoffer/${d.id}/`
            })

        });
    }

    getActionButton() {
        if (this.state.executing)
            return (
                <a className="mt-8 py-4 px-12 w-full text-center bg-blue no-underline text-grey-lighter text-xl font-bold cursor-pointer rounded-lg shadow shadow-3d-blue-sm">
                    Aguardando trade offer
                    <Dots minDots={1} delay={750}/>
                </a>
            );

        if (this.state.tradelink)
            return (
                <a onClick={this.redirectToPending} href={this.state.tradelink} target="_blank" className="mt-8 py-4 px-12 w-full text-center bg-blue no-underline text-grey-lighter text-xl font-bold cursor-pointer rounded-lg shadow shadow-3d-blue-sm">
                    Abrir tradeoffer
                </a>
            );

        if (!this.state.executing)
            return (
                <a onClick={this.onExecuteSteamOrder} className="mt-8 py-4 px-12 w-full text-center bg-blue no-underline text-grey-lighter text-xl font-bold cursor-pointer rounded-lg shadow shadow-3d-blue-sm hover:bg-blue-dark">Finalizar</a>
            )
    }

    redirectToPending() {
        redirect.bind(this)().steam().pending();
    }

    render() {
        let {order, inventory} = this.props;
        let {price, units} = this.state;

        return (
            <Fragment>
                <InventoryHud
                    order={order}
                    price={price}
                    units={units}
                />
                <div className="flex flex-wrap justify-start items-stretch p-4">
                    <p className="mb-2 p-4 w-full text-center text-2xl text-grey-darkest">Escolha as skin que você deseja utilizar na troca:</p>

                    {
                        Object.values(inventory).map((item) => (
                            <SteamItem
                                key={item.assetid}
                                onModifySelection={this.onModifySelection.bind(this)}
                                item={item}
                            />
                        ))
                    }

                    {
                        this.getActionButton()
                    }

                </div>
            </Fragment>
        );
    }
}

const mapDispatchToProps = dispatch => ({
    executeSteamOrder: (id, data) => dispatch(executeSteamOrder(id, data)),
    getInventory: (steamid) => dispatch(getInventory(steamid)),
    setAccent: (color) => dispatch(setAccent(color)),
    setAvatar: (visible) => dispatch(setAvatar(visible)),
    setWidth: (width) => dispatch(setWidth(width))
});

const mapStateToProps = (state, props) => ({
    inventory: state.inventory,
    steamid: props.order.payer_steam_id,
});

export default withRouter(connect(
    mapStateToProps, mapDispatchToProps
)(Inventory));