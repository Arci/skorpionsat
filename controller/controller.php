<?php

interface Controller {
    
    function save($object);
    
    function update($updatedObject);
    
    function delete($object);

    function loadByID($id);

    function loadAll($limit);
    
}