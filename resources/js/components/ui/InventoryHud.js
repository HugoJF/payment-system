import React, {Component} from 'react';
import {Motion, spring} from "react-motion";


function Exclamation() {
    return (
        <svg className="h-12 w-12 mx-2" version="1.1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 286.054 286.054">
            <path style={{fill: '#E2574C'}}
                  d="M143.027,0C64.04,0,0,64.04,0,143.027c0,78.996,64.04,143.027,143.027,143.027c78.996,0,143.027-64.022,143.027-143.027C286.054,64.04,222.022,0,143.027,0z M143.027,259.236c-64.183,0-116.209-52.026-116.209-116.209S78.844,26.818,143.027,26.818s116.209,52.026,116.209,116.209S207.21,259.236,143.027,259.236z M143.036,62.726c-10.244,0-17.995,5.346-17.995,13.981v79.201c0,8.644,7.75,13.972,17.995,13.972c9.994,0,17.995-5.551,17.995-13.972V76.707C161.03,68.277,153.03,62.726,143.036,62.726z M143.036,187.723c-9.842,0-17.852,8.01-17.852,17.86c0,9.833,8.01,17.843,17.852,17.843s17.843-8.01,17.843-17.843C160.878,195.732,152.878,187.723,143.036,187.723z"/>
        </svg>
    )
}

function MaxUnitsWarning({order}) {
    return <>
        <div><Exclamation/></div>
        <div className="flex flex-col items-stretch">
            <p className="break-words uppercase font-medium text-center text-base text-grey-darkest tracking-wide">O seu pedido está acima do limite máximo permitido!</p>
            <small className="mt-2 text-center font-light text-sm text-grey-dark">Por favor selecione itens de menor valor para que seu pedidos fique abaixo do limite de {order.max_units} {productNamePlural(order)}</small>
        </div>
    </>
}

function MinUnitsWarning({order}) {
    return <>
        <div className="px-2">
            <Exclamation/>
        </div>
        <div className="flex flex-col items-stretch">
            <p className="break-words uppercase font-medium text-center text-base text-grey-darkest tracking-wide">O seu pedido está abaixo do limite mínimo permitido!</p>
            <small className="mt-2 text-center font-light text-sm text-grey-dark">Por favor selecione mais itens ou itens de maior valor para que seu pedidos fique acima do limite de {order.min_units} {order.min_units === 1 ? productNameSingular(order) : productNamePlural(order)}!</small>
        </div>
    </>
}

function Units({order, units}) {
    return <h1 className="flex px-2 flex-col font-light text-base whitespace-no-wrap md:text-xl text-grey-dark">
        <span>Total:</span>
        <Motion defaultStyle={{x: 0}} style={{x: spring(Math.abs(units))}}>
            {value => {
                let val = Math.round(value.x);

                return (
                    <p className="font-normal text-2xl md:text-3xl text-grey-darker">
                        <span>{val} </span>
                        <span className="text-lg text-grey">{val === 1 ? productNameSingular(order) : productNamePlural(order)}</span>
                    </p>
                )
            }}
        </Motion>
    </h1>
}

function Price({price}) {
    return <h1 className="flex px-2 flex-col font-light text-base whitespace-no-wrap md:text-xl text-grey-dark">
        <span>Valor total:</span>
        <Motion defaultStyle={{x: 0}} style={{x: spring(Math.abs(price))}}>
            {value => {
                let val = value.x.toFixed(2);

                return <span className="font-normal text-2xl md:text-3xl text-grey-darker">${val}</span>
            }}
        </Motion>
    </h1>
}

function Discount({discount, maxDiscount}) {
    let x = spring(Math.ceil(discount * 100));
    let max = Math.ceil(maxDiscount * 100);

    return <h1 className="flex px-2 flex-col font-light text-base whitespace-no-wrap md:text-xl text-grey-dark">
        <span>Desconto:</span>
        <Motion defaultStyle={{x: 0}} style={{x: x}}>
            {value => {
                let disc = Math.round(value.x);
                let ratio = disc / max;
                let color = ratio > 0.8 ? 'text-green-dark' : '';
                color = ratio < 0.4 ? 'text-red-dark' : color;

                return <span className="font-normal text-2xl md:text-3xl text-grey-darker">
                    <span className={color}>{disc}%</span>
                    <span className="text-lg text-grey"> / {max}%</span>
                </span>
            }}
        </Motion>
    </h1>
}

function productNameSingular(order) {
    return order.product_name_singular || 'unidade';
}

function productNamePlural(order) {
    return order.product_name_plural || 'unidades';
}

export default function InventoryHud({finalizeButton, order, price, units}) {
    let {unit_price, discount_per_unit, unit_price_limit, min_units, max_units} = order;
    let roundedUnits = Math.round(units);

    let perUnitPrice = unit_price - (roundedUnits * discount_per_unit);
    perUnitPrice = perUnitPrice < unit_price_limit ? unit_price_limit : perUnitPrice;
    let discount = 1 - perUnitPrice / unit_price;
    let maxDiscount = 1 - unit_price_limit / unit_price;

    return (
        <div className="z-50 fixed pin-t pin-l pin-r flex justify-center w-full">
            <div className="xl:w-1/2 w-full">
                <div className="px-3 py-1 bg-white rounded-t-none rounded-lg shadow-md overflow-hidden bg-grey-lightest">
                    <div className="flex justify-between items-center p-4">
                        <Price price={price}/>
                        <Units units={units} order={order}/>
                        <Discount discount={discount} maxDiscount={maxDiscount}/>

                        {
                            roundedUnits > max_units &&
                            <MaxUnitsWarning order={order}/>
                        }

                        {
                            roundedUnits < min_units &&
                            <MinUnitsWarning order={order}/>
                        }

                        {
                            (roundedUnits <= max_units && roundedUnits >= min_units) && <div>{finalizeButton}</div>
                        }

                    </div>
                </div>
            </div>
        </div>
    );
}