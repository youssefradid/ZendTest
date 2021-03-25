<?php

class Application_Model_DbTable_Continent implements Application_Model_DbTable_SoapService
{

    public $_sCode;
    public $_sName;


    public function getCode() {
        return $this->__sCode;
    }

    public function setCode( $_sCode) {
        $this->_sCode = $_sCode;
    }



    public function getName() {
        return $this->_sName;
    }

    public function setName( $_sName) {
        $this->_sName = $_sName;
    }

    public function CallApi(){
        $continents = array();
        $client = new Zend_Soap_Client('http://webservices.oorsprong.org/websamples.countryinfo/CountryInfoService.wso?WSDL');
        $result = $client->ListOfContinentsByName();
        $conti=$result->ListOfContinentsByNameResult->tContinent;
        foreach ($conti as $a){
            $continent = new Application_Model_DbTable_Continent();
            $continent->setName($a->sName);
            $continent->setCode($a->sCode);
            array_push($continents, $continent);
        }
        return $continents;
    }

}

