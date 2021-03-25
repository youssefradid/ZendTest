<?php

class Application_Model_DbTable_Pays implements Application_Model_DbTable_SoapService
{

    public $_sName;
    public $_sISOCode;
    public $_sCapitalCity;
    public $_sCountryFlag;
    public $_sPhoneCode;
    public $_CCode;
    public $_sCurrencyISOCode;
    public $_language = array();


    public function getCCode() {
        return $this->_CCode;
    }

    public function setCCode( $_CCode) {
        $this->_CCode = $_CCode;
    }
/*** drapeau url */
    public function getCountryFlag() {
        return $this->_sCountryFlag;
    }

    public function setCountryFlag( $_sCountryFlag) {
        $this->_sCountryFlag = $_sCountryFlag;
    }


/*** nom de pays */
    public function getName() {
        return $this->_sName;
    }

    public function setName( $_sName) {
        $this->_sName = $_sName;
    }

/*** code iso */
    public function getISOCode() {
        return $this->_sISOCode;
    }

    public function setISOCode( $_sISOCode) {
        $this->_sISOCode = $_sISOCode;
    }
/*** capitale */
    public function getCapitalCity() {
        return $this->_sCapitalCity;
    }

    public function setCapitalCity( $_sCapitalCity) {
        $this->_sCapitalCity = $_sCapitalCity;
    }


/*** phone code */

    public function getPhoneCode() {
        return $this->_sPhoneCode;
    }

    public function setPhoneCode( $_sPhoneCode) {
        $this->_sPhoneCode = $_sPhoneCode;
    }


/***** ISO CODE *******/

    public function getCurrencyISOCode() {
        return $this->_sCurrencyISOCode;
    }

    public function setCurrencyISOCode( $_sCurrencyISOCode) {
        $this->_sCurrencyISOCode = $_sCurrencyISOCode;
    }


/***** languages  ******/

public function getLanguages() {
    return $this->$_language;
}

public function setLanguages($_language) {
    $this->_language = $_language;
}

public function CallApi(){
    $listPays = array();
    $client = new Zend_Soap_Client('http://webservices.oorsprong.org/websamples.countryinfo/CountryInfoService.wso?WSDL');
    $client->FullCountryInfoAllCountries()
                    ->FullCountryInfoAllCountriesResult
                    ->tCountryInfo;

    $conti = $client->FullCountryInfoAllCountries()
                    ->FullCountryInfoAllCountriesResult
                    ->tCountryInfo;
    foreach ($conti as $a){
        $pays = new Application_Model_DbTable_Pays();
        $pays->setName($a->sName);
        $pays->setCCode($a->sContinentCode);
        $pays->setCountryFlag($a->sCountryFlag);
        $pays->setISOCode($a->sISOCode);
        array_push($listPays, $pays);
        }
        return $listPays;
}



public function AfficherPays($nom){

    $infoPays = array();
    $languages = array();

    $client = new Zend_Soap_Client('http://webservices.oorsprong.org/websamples.countryinfo/CountryInfoService.wso?WSDL');
    $a = new \stdClass();
    $a->sCountryISOCode = $nom;
    $result = $client->FullCountryInfo($a);
    $conti=$result->FullCountryInfoResult;
            $pays = new Application_Model_DbTable_Pays();
            $pays->setISOCode($conti->sISOCode);
            $pays->setName($conti->sName);
            $pays->setCountryFlag($conti->sCountryFlag);
            $pays->setCapitalCity($conti->sCapitalCity);
            $pays->setPhoneCode($conti->sPhoneCode);
            $pays->setCurrencyISOCode($conti->sCurrencyISOCode);

            if(isset($conti->Languages->tLanguage)){
                $total = count((array)$conti->Languages->tLanguage);
                if($total==2){
                    array_push($languages,$conti->Languages->tLanguage->sName);
                  }else if($total>2){
                foreach ($conti->Languages->tLanguage as $a){
                array_push($languages,$a->sName);
                }
            }else{
                array_push($languages,"not found!");
            }
            $pays->setLanguages($languages);
            array_push($infoPays, $pays);

}

return $infoPays;
}

 }