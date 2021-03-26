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

        if(isset($_POST['search']) || isset($_POST['continents'])) {
            $listPays = array_filter($listPays, function($x) {
                if(!empty($_POST['search']) && !empty($_POST['continents'])){
                    return strpos(strtoupper($x->getName()), strtoupper($_POST['search'])) === 0
                    && in_array($x->getCCode(), $_POST['continents']);
                }
                elseif(!empty($_POST['search']))
                    return strpos(strtoupper($x->getName()), strtoupper($_POST['search'])) === 0;
                elseif(!empty($_POST['continents']))
                    return in_array($x->getCCode(), $_POST['continents']);
            });
        }
        $this->view->dataPays = $listPays;
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
