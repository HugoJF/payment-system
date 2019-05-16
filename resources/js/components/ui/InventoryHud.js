import React, {Component, Fragment} from 'react';
import {Motion, spring} from "react-motion";
import {Collapse} from "react-collapse";

class InventoryHud extends Component {
    render() {
        let {order, price, units} = this.props;

        return (
            <div className="z-50 fixed pin-t pin-l pin-r flex justify-center w-full h-16">
                <div className="xl:w-1/2 w-full">
                    <div className="mt-4 bg-grey-lightest rounded-lg shadow-md overflow-hidden bg-grey-lightest border border-blue-dark">
                        <div className="flex justify-between items-center p-4">
                            <h1 className="flex px-2 flex-col font-light text-base whitespace-no-wrap md:text-xl text-grey-dark">
                                <span>Valor total:</span>
                                <Motion defaultStyle={{x: 0}} style={{x: spring(Math.abs(price))}}>
                                    {value => {
                                        let val = value.x.toFixed(2);

                                        return <span className="font-normal text-2xl md:text-3xl text-grey-darkest">${val}</span>
                                    }}
                                </Motion>
                            </h1>
                            <h1 className="flex px-2 flex-col font-light text-base whitespace-no-wrap md:text-xl text-grey-dark">
                                <span>Total de unidades:</span>
                                <Motion defaultStyle={{x: 0}} style={{x: spring(Math.abs(units))}}>
                                    {value => {
                                        let val = Math.round(value.x);

                                        return <span className="font-normal text-2xl md:text-3xl text-grey-darkest">{val} {val === 1 ? 'unidade' : 'unidades'}</span>
                                    }}
                                </Motion>
                            </h1>


                            {
                                units > order.max_units &&
                                <Fragment>
                                    <div>
                                        <svg className="h-12 w-12 mx-2" version="1.1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 286.054 286.054">
                                            <path style={{fill: '#E2574C'}}
                                                  d="M143.027,0C64.04,0,0,64.04,0,143.027c0,78.996,64.04,143.027,143.027,143.027c78.996,0,143.027-64.022,143.027-143.027C286.054,64.04,222.022,0,143.027,0z M143.027,259.236c-64.183,0-116.209-52.026-116.209-116.209S78.844,26.818,143.027,26.818s116.209,52.026,116.209,116.209S207.21,259.236,143.027,259.236z M143.036,62.726c-10.244,0-17.995,5.346-17.995,13.981v79.201c0,8.644,7.75,13.972,17.995,13.972c9.994,0,17.995-5.551,17.995-13.972V76.707C161.03,68.277,153.03,62.726,143.036,62.726z M143.036,187.723c-9.842,0-17.852,8.01-17.852,17.86c0,9.833,8.01,17.843,17.852,17.843s17.843-8.01,17.843-17.843C160.878,195.732,152.878,187.723,143.036,187.723z"/>
                                        </svg>
                                    </div>
                                    <div className="flex flex-col items-stretch">
                                        <p className="break-words uppercase font-medium text-center text-base text-grey-darkest tracking-wide">Valor acima do permitido! E mais um pouco de texto!</p>
                                        <small className="mt-2 text-center font-light text-sm text-grey-dark">E mais um pouco pra ver a zica que fica se tem muito texto nessa porra</small>
                                    </div>
                                </Fragment>
                            }

                            {
                                units <= order.max_units &&
                                <a href="#" className="py-4 px-12 bg-blue no-underline text-grey-lighter text-xl font-bold rounded-lg shadow shadow-3d-blue-sm hover:bg-blue-dark">Finalizar</a>
                            }

                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

export default InventoryHud;