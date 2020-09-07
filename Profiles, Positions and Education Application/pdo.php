<?php
try{
    $pdo = new pdo( 'mysql:host=127.0.0.1:3306;dbname=profile',
                    'fred',
                    'zap',
                    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    //die(json_encode(array('outcome' => true)));
}
catch(PDOException $ex){
    //die(json_encode(array('outcome' => false, 'message' => 'Unable to connect')));
}
?>
