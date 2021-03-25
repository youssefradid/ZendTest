<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {

        Zend_Loader::loadClass('Zend_Cache');
        $frontendOptions = array(
          'lifetime' => 60 * 5, // 5 minutes
          'automatic_serialization' => true,
        );
        $backendOptions = array(
           'cache_dir' =>  APPLICATION_PATH . '\cache',
           'file_name_prefix' => 'zend_cache_query',
           'hashed_directory_level' => 2,
        );
        $query_cache = Zend_Cache::factory('Core', 'File', $frontendOptions,$backendOptions);


    }

    public function countriesAction()
    {


        /***liste des continents *******/
        $continents = array();
        $z = new Application_Model_DbTable_Continent();
        $continents = $z->CallApi();
        //l'envoie les donneés vers front
        $this->view->data = $continents;
        /***liste des pays *******/
        $listPays = array();
        $y = new Application_Model_DbTable_Pays();
        $listPays = $y->CallApi();

                            /*********** liste of countries by continent **********/

        if (isset($_GET['cont'])) {
            $listPays = array();
            $listPays1 = array();
            $d = new Application_Model_DbTable_Pays();
            $listPays = $d->CallApi();
            foreach ($listPays as $a) {
                if ($a->getCCode() == $_GET['cont']) {
                    $pays = new Application_Model_DbTable_Pays();
                    $pays->setName($a->getName());
                    $pays->setCountryFlag($a->getCountryFlag());
                    $pays->setISOCode($a->getISOCode());
                    array_push($listPays1, $pays);
                }
            }
            $this->view->dataPays = $listPays1;
        } else if (isset($_POST['search'])) {
            $listPays = array_filter($listPays, function ($a) {
                return strpos(strtoupper($a->getName()), strtoupper($_POST['search'])) === 0;
            }
            );
            $this->view->dataPays = $listPays;
        } else {
            $this->view->dataPays = $listPays;
        }
    }

    public function paysInfoAction()
    {

        if (isset($_GET['nom'])) {
            $infoPays = array();
            $d = new Application_Model_DbTable_Pays();
            $infoPays = $d->AfficherPays($_GET['nom']);
            //l'envoie les donneés vers front
            $this->view->dataInfosPays = $infoPays;

        }
    }

}
