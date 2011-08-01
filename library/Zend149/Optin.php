<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Optin
 *
 * @author mvengelshoven
 */
class Zend149_Optin {

    /**
     *
     * @param Zend149_Optin_Backend $backend
     */
    protected $_backend;

    public function __construct(Zend149_Optin_Backend $backend) {
        $this->setBackend($backend);
    }

    public function create($type, $options = array()) {
        
    }

    public function setBackend(Zend149_Optin_Backend $backend) {
        $this->_backend = $backend;
    }
    
}
