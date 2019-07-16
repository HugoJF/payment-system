export const calculateUnits = (total: number, unitPrice: number, discountPerUnit: number, unitPriceLimit: number): number => {
    let u: number = 0;
    let pricePerUnit: number = unitPrice;

    while (total >= pricePerUnit) {
        u++;
        total -= pricePerUnit;
        pricePerUnit -= discountPerUnit;
        if (pricePerUnit < unitPriceLimit)
            pricePerUnit = unitPriceLimit;
    }

    return u + total / pricePerUnit;
};