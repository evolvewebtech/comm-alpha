<?php
/* 
 * dialoghi ok ed errore. In base all'azione che compio i tag #code-ok
 * e #code-err sono sostituiti dai rispettivi messaggi. Vedere le funzioni success
 * per i file amministrazione*
 */

?>
<div id="dialogOK" title="Ok!">
    <fieldset style="background-color:#00CF00">
        <p id="code-ok"></p>
        <p>Operazione avvenuta con successo.</p>
    </fieldset>
</div>
<div id="dialogERR" title="Ops!">
    <fieldset style="background-color:red">
        <p id="code-err"></p>
        <p>OPS! Si &egrave; verificato un errore, riprova.<br />Se l'errore persiste contatta l'assistenza.</p>
    </fieldset>
</div>