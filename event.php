<?php
namespace addon\plugin\payment;
use \core\cls\browser as browser;
use \core\cls\core as core;

class event extends module{

    /*
	 * Edite or insert forum
	 * @param array $e, form properties
	 * @return array, form properties
	 */
    public function btnOnclickSaveSettings($e){
        if($this->isLogedin() && $this->hasAdminPanel() ){
            if(array_key_exists('txtPaylineApi',$e)){
                $registry = core\registry::singleton();
                $registry->set('payment','paylineApi',$e['txtPaylineApi']['VALUE']);
                return browser\msg::modalSuccessfull($e);
            }
            return browser\msg::modalEventError($e);
        }
        return browser\msg::modalNoPermission($e);
    }
}
