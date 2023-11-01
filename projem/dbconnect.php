<?php
try{
$db = new PDO("mysql:host=db;dbname=yavuzlar","user","user" );

}
catch(PDOException $e){
    echo $e->getMessage();
    echo "basarisiz";
}
?>