<?php
    echo "Prova Event-Driven Programming <br /n><br /n>";
    require_once dirname(__FILE__).'/../events/Dispatcher.php';    
?>
<form method="GET" ACTION="<?=$_SERVER['PHP_SELF']?>">
    <input type='submit' name='event' value='View' />
    <input type='submit' name='event' value='Edit' />
</form>

<?php
    function handle() {
        $event = $_GET['event'];
        $disp = new dispatcher($event);
        $disp->handle_the_event();
    }
    
    if (!empty($_GET)) {
        handle();
    }
    
?>
