export const calculateUnits = (total, unitPrice, discountPerUnit, unitPriceLimit) => {
    let u = 0;
    let pricePerUnit = unitPrice;

    while (total >= pricePerUnit) {
        u++;
        total -= pricePerUnit;
        pricePerUnit -= discountPerUnit;
        if (pricePerUnit < unitPriceLimit)
            pricePerUnit = unitPriceLimit;
    }

    return u + total / pricePerUnit;
};