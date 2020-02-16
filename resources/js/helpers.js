export const calculateUnits = (balance, unitPrice, discountPerUnit, unitPriceLimit) => {
    let units = 0;
    let pricePerUnit = unitPrice;

    while (balance >= pricePerUnit) {
        units++;
        balance -= pricePerUnit;
        pricePerUnit -= discountPerUnit;
        if (pricePerUnit < unitPriceLimit) {
            pricePerUnit = unitPriceLimit;
        }
    }

    // Calculate the last partial unit with remainder balance
    return units + balance / pricePerUnit;
};
