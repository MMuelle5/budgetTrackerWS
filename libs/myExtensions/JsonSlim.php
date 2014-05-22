<?php
class jsonSlim extends \Slim\Slim {
    function outputArray($data) {
        switch($this->request->headers->get('Accept')) {
            case 'application/json':
            default:
                $this->response->headers->set('Content-Type', 'application/json');
                echo json_encode($data);        
        }       
    } 
}

?>