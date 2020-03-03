import React, {useEffect} from 'react';
import {pusher} from "../app";

export default function PendingOrder({id}) {
    useEffect(() => {
        let channel = pusher.subscribe('orders');
        console.log(`Binding events for order ID: ${id}`);

        setTimeout(reload, 60000);

        channel.bind(id, (data) => {
            console.log(`Event from: ${id}`, data);
            reload();
        });
    }, []);

    function reload() {
        window.location.reload();
    }

    return <div className="spinner-border w-16 h-16 mt-8 text-grey"/>;
}
