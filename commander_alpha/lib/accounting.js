
/**
 * Verifica se numero è intero
 *
 */
function isInt(number) {
    var str = number.toString();   
    var numericExpression = /^[0-9]+$/;  
    if (str.match(numericExpression)) return true;  
    else return false; 
}

        
/**
 * Formattazione numero
 * Se numero intero -> restituito così com'è
 * Se numero con decimali -> restituito con n decimali
 * 
 */
function formatMoney(number, decimal, retInt) {
    try {
        var num = parseFloat(number);
        if ( (isInt(num)) && (retInt) ) return num;  
        return num.toFixed(decimal);
    }
    catch(e) {
        return number;
    }
}
