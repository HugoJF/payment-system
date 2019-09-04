import React from 'react';
import ReactDOM from 'react-dom';
import 'nprogress/nprogress.css'
import Inventory from "./components/ui/Inventory";
import Pusher from 'pusher-js';
import PendingTradeoffer from "./components/PendingTradeoffer";

// TODO: improve selector

export const pusher = new Pusher('c658b2d0b66466dceb46', {
    cluster: 'us2',
    forceTLS: true
});

let root = document.getElementById('inventory');
if (root)
    ReactDOM.render(<Inventory csrf={csrf} order={order} inventory={inventory}/>, root);

let pending = document.getElementById('pending-tradeoffer');
if (pending) {
    let id = pending.getAttribute('data-tradeoffer-id');
    ReactDOM.render(<PendingTradeoffer id={id}/>, pending);
}

