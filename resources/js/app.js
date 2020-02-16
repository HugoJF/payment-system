import React from 'react';
import ReactDOM from 'react-dom';
import Inventory from "./components/ui/Inventory";
import Pusher from 'pusher-js';
import PendingOrder from "./components/PendingOrder";

export const pusher = new Pusher('c658b2d0b66466dceb46', {
    cluster: 'us2',
    forceTLS: true
});

const mappings = {
    'inventory': Inventory,
    'pending-order': PendingOrder,
};

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
