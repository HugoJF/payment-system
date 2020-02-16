import React, {useState, useRef} from 'react';
import SteamItem from "./SteamItem";
import InventoryHud from "./InventoryHud";
import sumBy from 'lodash.sumby';
import {calculateUnits} from "../../helpers";

export default function Inventory({csrf, order, inventory}) {
    const [loading, setLoading] = useState(false);
    const [units, setUnits] = useState(0);
    const [price, setPrice] = useState(0);
    const selectedItems = useRef({});

    inventory = JSON.parse(inventory);
    order = JSON.parse(order);
    let items = Object.values(inventory);

    function onModifySelection(item, selected) {
        let {unit_price, discount_per_unit, unit_price_limit} = order;

        // Modify list according to action
        if (selected) {
            selectedItems.current[item.assetid] = item;
        } else {
            delete selectedItems.current[item.assetid];
        }

        // Calculate total selected value
        let totalValue = sumBy(Object.values(selectedItems.current), 'price');

        // Calculate how many units can be bought with total value
        let calc = calculateUnits(totalValue, unit_price, discount_per_unit, unit_price_limit);

        // Update state
        setUnits(calc);
        setPrice(totalValue / 100);
    }

    function onSendTradeoffer() {
        // Avoid mouseClick getting cancelled by delaying loading state
        setTimeout(() => {
            setLoading(true);
        }, 0.001);
    }

    return (
        <>
            <InventoryHud
                order={order}
                price={price}
                units={units}
                finalizeButton={
                    <a href="#send-trade-offer" className="py-4 px-12 w-full text-center bg-blue no-underline text-grey-lighter text-xl font-bold cursor-pointer rounded-lg shadow shadow-3d-blue-sm hover:bg-blue-dark">Finalizar</a>
                }
            />

            <div className="flex flex-wrap justify-start items-stretch p-4">
                {/* Inventory loaded */}
                {
                    items.length !== 0 &&
                    <h3 className="mb-2 p-4 w-full text-center text-grey-darkest">Escolha as skin que você deseja utilizar na troca:</h3>
                }

                {/* Item list */}
                {
                    items.map((item) => (
                        <SteamItem
                            key={item.assetid}
                            onModifySelection={onModifySelection}
                            item={item}
                        />
                    ))
                }
                {/* Send tradeoffer button */}
                {
                    !loading && <button id="send-trade-offer" type="submit" onClick={onSendTradeoffer} className={`${loading ? 'hidden ' : ''}py-4 px-12 w-full text-center bg-blue no-underline text-grey-lighter text-xl font-bold cursor-pointer rounded-lg shadow shadow-3d-blue-sm hover:bg-blue-dark`}>Enviar trade offer!</button>
                }
            </div>
        </>
    );
}
