import React from 'react';
import ReactDOM from 'react-dom';
import Inventory from "./components/ui/Inventory";
import Pusher from 'pusher-js';
import PendingOrder from "./components/PendingOrder";

// TODO: improve selector

export const pusher = new Pusher('c658b2d0b66466dceb46', {
    cluster: 'us2',
    forceTLS: true
});

let root = document.getElementById('inventory');
if (root) {
    ReactDOM.render(<Inventory csrf={csrf} order={order} inventory={inventory}/>, root);
}

let pending = document.getElementById('pending-order');
if (pending) {
    let id = pending.getAttribute('data-order');
    ReactDOM.render(<PendingOrder id={id}/>, pending);
}

