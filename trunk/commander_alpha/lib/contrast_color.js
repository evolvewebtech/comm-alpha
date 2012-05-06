

function contrastColor(color) {
    
    //eliminazione carattere iniziale '#'
    color = color.replace('#','');
    
    var type = 0;
    
    if (color.length == 3) {
        type = 0;
    }
    else if (color.length == 6) {
        type = 1;
    }
    else return '#000000' //colore nero
    
    var R = '';
    var G = '';
    var B = '';
    
    //Estrazione singole componenti colore da stringa
    if (type == 0) {
        R = color.substring(0,1);
        G = color.substring(1,2);
        B = color.substring(2,3);
        R = R+R;
        G = G+G;
        B = B+B;
    }
    if (type == 1) {
        R = color.substring(0,2);
        G = color.substring(2,4);
        B = color.substring(4,6);
    }
    
    R = parseInt(R, 16);
    G = parseInt(G, 16);
    B = parseInt(B, 16);
    
    R = parseFloat(R);
    G = parseFloat(G);
    B = parseFloat(B);
    
    // Counting the perceptive luminance - human eye favors green color... 
    var lum = 1 - ( 0.299 * R + 0.587 * G + 0.114 * B) / 255;
    
    if (lum < 0.5) return '#000000'; // bright colors - black font
    else return '#ffffff'; // dark colors - white font
}
