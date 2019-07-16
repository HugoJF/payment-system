export interface Order {
    id: string,
    preset_amount: number,
    return_url: string,
    reason: string,
    paid: boolean,
    avatar: string,
    payer_tradelink: string,
    unit_price: number,
    discount_per_unit: number,
    product_name_singular: string,
    product_name_plural: string,
    min_units: number,
    max_units: number,
    unit_price_limit: number,
    orderable: PayPalOrder & SteamOrder,
    orderable_id: number,
    orderable_type: 'App\\MPOrder' | 'App\\PayPalOrder' | 'App\\SteamOrder'
}

export interface PayPalOrder {
    status: string,
    paypal_link: string,
}

export interface SteamOrder {
    tradeoffer_state: number,
}

export interface Preference {
    init_point: string,
}

export interface SteamItemInformation {
    appid: string,
    contextid: string,
    assetid: string,
    market_hash_name: string,
    price: string,
    icon_url: string,
}

export interface SteamItemIdentification {
    appid: string,
    contextid: string,
    assetid: string,
}

export type setAccentType = (color?: string) => void;
export type setWidthType = (width?: string) => void;
export type setAvatarType = (avatar?: boolean, url?: string) => void;

export type getOrderType = (orderId: string) => Promise<Order>
export type getInventoryType = (steamid: string) => void;

export type executeMpOrderType = (orderId: string) => Promise<Order>
export type initMercadoPagoOrderType = (orderId: string) => Promise<Order>

export type executePayPalOrderType = (orderId: string) => Promise<Order>
export type initPayPalOrderType = (orderId: string) => Promise<Order>

export type executeSteamOrderType = (orderId: string, data: SteamItemIdentification[]) => Promise<Order>
export type initSteamOrderType = (orderId: string) => Promise<Order>