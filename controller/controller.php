<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/Skorpionsat/site/controller/database.php");
require_once(DOCUMENT_ROOT."/logger.php");
interface Controller {
    
    function save($object);
    
    function update($updatedObject);
    
    function delete($object);

    function loadByID($id);

    function loadAll($limit);
    
}