import React, {useState, useEffect} from 'react';
import pick from 'lodash.pick';

export default function SteamItem({onModifySelection, item}) {
    const [selected, setSelected] = useState(false);
    let {market_hash_name, price, icon_url} = item;

    useEffect(() => {
        if (onModifySelection)
            onModifySelection(item, selected);
    }, [selected]);

    function onToggleSelect() {
        setSelected(!selected);
    }

    return (
        <div className="w-full sm:w-1/2 md:w-1/3 p-4 text-4xl">
            {selected && <input type="hidden" name="items[]" value={JSON.stringify(pick(item, ['appid', 'contextid', 'assetid', 'market_hash_name']))}/>}
            <a onClick={onToggleSelect} className={`trans relative flex flex-col justify-center items-stretch text-grey-darkest rounded-lg cursor-pointer no-underline overflow-hidden ${selected ? 'bg-white shadow-md' : 'bg-transparent hover:shadow hover:bg-white'}`}>
                <div style={{height: selected ? '0.75rem' : '0'}} className={`trans absolute pin-t ${selected ? 'shadow' : ''} w-full bg-blue-dark`}/>
                <div className="py-3 px-10 sm:px-5">
                    <img className="w-full" src={icon_url}/>
                    <h3 className="text-sm font-normal">{market_hash_name}</h3>
                    <div className="mt-4 flex justify-start">
                        <span className="text-lg font-semibold">${(parseInt(price) / 100).toFixed(2)}</span>
                    </div>
                </div>
            </a>
        </div>
    )
}