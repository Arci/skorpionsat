<?php

require_once(dirname(__FILE__)."/database.php");
require_once(DOCUMENT_ROOT."/logger.php");
interface Controller {
    
    function save($object);
    
    function update($updatedObject);
    
    function delete($object);

    function loadByID($id);

    function loadAll($limit);
    
}