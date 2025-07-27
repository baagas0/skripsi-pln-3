/**
 * Tangible Benefits Calculation Helper
 * Provides utility functions for calculating tangible benefit components
 */

// Calculate the total cost from components and their operators
function calculateTangibleTotal(components) {
    let total = 0;
    
    if (!components || components.length === 0) {
        return total;
    }
    
    // Process each component
    for (let i = 0; i < components.length; i++) {
        const component = components[i];
        
        if (!component.name || !component.price) {
            continue;
        }
        
        // Start with the component's base price
        let componentValue = parseFloatSafe(component.price);
        
        // Process sub-components if they exist
        if (component.sub_items && component.sub_items.length > 0) {
            for (let j = 0; j < component.sub_items.length; j++) {
                const subItem = component.sub_items[j];
                
                if (!subItem.price) {
                    continue;
                }
                
                const subValue = parseFloatSafe(subItem.price);
                const operator = subItem.operator || '*';
                
                // Apply the operation based on operator
                switch (operator) {
                    case '*':
                        componentValue *= subValue;
                        break;
                    case '+':
                        componentValue += subValue;
                        break;
                    case '-':
                        componentValue -= subValue;
                        break;                    case '/':
                        if (subValue !== 0) { // Prevent division by zero
                            componentValue /= subValue;
                        } else {
                            console.warn('Division by zero detected in component calculation');
                            // Keep the current value unchanged
                        }
                        break;
                }
            }
        }
        
        // Add the component's calculated value to the total
        total += componentValue;
    }
    
    return total;
}

// Safe parsing of float values from string inputs
function parseFloatSafe(value) {
    if (typeof value === 'number') {
        return value;
    }
    
    if (!value) {
        return 0;
    }
    
    // Remove all non-numeric characters except period/dot and comma
    value = value.toString().replace(/[^\d,.]/g, '');
    
    // Replace comma with dot for decimal
    value = value.replace(/,/g, '.');
    
    return parseFloat(value) || 0;
}

// Format a number as currency
function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 2
    }).format(amount);
}

// Calculate and show component values in a formula display
function displayComponentFormula(componentItem) {
    const componentName = componentItem.find('input[name$="[name]"]').val() || 'Component';
    const componentPrice = parseFloatSafe(componentItem.find('.sub-component-item:first input[name$="[price]"]').val());
    
    let result = componentPrice;
    let formula = componentName + ': ' + formatCurrencySimple(componentPrice);
    
    // Process sub-components
    componentItem.find('.sub-component-item:not(:first)').each(function() {
        const subName = $(this).find('input[name$="[sub_name]"]').val() || '';
        const subPrice = parseFloatSafe($(this).find('input[name$="[price]"]').val());
        const operator = $(this).find('select[name$="[operator]"]').val();
        
        if (subPrice || subPrice === 0) {
            formula += ' ' + operator + ' ' + (subName ? subName + ' (' : '') + formatCurrencySimple(subPrice) + (subName ? ')' : '');
            
            // Update the running result
            switch (operator) {
                case '*':
                    result *= subPrice;
                    break;
                case '+':
                    result += subPrice;
                    break;
                case '-':
                    result -= subPrice;
                    break;                case '/':
                    if (subPrice !== 0) {
                        result /= subPrice;
                    } else {
                        console.warn('Division by zero detected in formula display');
                        // Add warning to formula
                        formula += ' (⚠️ division by zero)';
                    }
                    break;
            }
        }
    });
    
    formula += ' = ' + formatCurrencySimple(result);
    
    return {
        formula: formula,
        result: result
    };
}

// Simpler currency format for formula display
function formatCurrencySimple(amount) {
    return new Intl.NumberFormat('id-ID', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 2
    }).format(amount);
}
