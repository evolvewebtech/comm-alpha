
var mesi = new Array('Gennaio','Febbraio','Marzo','Aprile','Maggio','Giugno', 'Luglio','Agosto','Settembre','Ottobre','Novembre','Dicembre');

function formato_data_ora(data, tipo) {
        
        if ((data.length!=10) && (data.length!=19)){
            return 0;
        }
        
        var anno = data.substring(0,4);
        var mese = data.substring(5,7);
        var giorno = data.substring(8,10);
        
        var ora = data.substring(11,19);
        
        var retStr = '';
        
        if(anno>1900 && anno<3000 && mese>0 && mese<13 && giorno>0 && giorno<32){
            if(tipo=='/' || tipo=='-'){
                retStr = giorno+tipo+mese+tipo+anno;
                if (ora != '') retStr = retStr + ' ' + ora;
                return retStr;
            }
            else{
                if(tipo=='TXT'){
                    retStr = giorno+" "+mesi[parseInt(mese)-1]+" "+anno;
                    if (ora != '') retStr = retStr + ' ' + ora;
                    return retStr;
                }
                return false;
            }
        }
        else{
            return 0;
        }
}