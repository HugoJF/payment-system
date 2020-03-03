import React from 'react';
import ReactDOM from 'react-dom';
import Pusher from 'pusher-js';
import Inventory from "./components/ui/Inventory";
import PendingOrder from "./components/PendingOrder";

console.log('Connecting Pusher to app', window['pusherAppKey']);

export const pusher = new Pusher(window.pusherAppKey, {
    cluster: 'us2',
    forceTLS: true
});

const mappings = {
    'inventory': Inventory,
    'pending-order': PendingOrder,
};

// TODO: figure out how to auto parse shit
for (const [dataReact, Component] of Object.entries(mappings)) {
    let selector = `[data-react="${dataReact}"]`;
    let elements = document.querySelectorAll(selector);

    console.log(`Found ${elements.length} elements with selector ${selector}`);

    for (let element of elements) {
        const data = {};

        for (const attr of element.attributes) {
            let name = attr.name.replace(/^data-/gi, '');
            data[name] = attr.value;
        }

        console.log(`Rendering component with: ${Object.keys(data).join(',')}`);

        ReactDOM.render(<Component {...data} />, element);
    }
}
