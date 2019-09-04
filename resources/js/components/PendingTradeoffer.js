import React, {useEffect} from 'react';
import {pusher} from "../app";

export default function PendingTradeoffer({id}) {
    useEffect(() => {
        let channel = pusher.subscribe('steam-tradeoffers');
        console.log(`Binding events for tradeoffer ID: ${id}`);

        channel.bind(id, function (data) {
            console.log(`Event from: ${id}`, data);
            reload();
        });
    }, []);

    function reload() {
        window.location.reload();
    }

    return (
        <>
            <p className="text-sm font-light text-grey tracking-normal">Tradeoffers podem demorar até 1 minuto para serem atualizadas</p>
            <div className="spinner-border w-16 h-16 mt-8 text-grey"/>
        </>
    );
}