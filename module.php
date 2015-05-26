<?php
namespace addon\plugin\payment;
use core\cls\browser as browser;
use core\cls\network as network;
use core\cls\core as core;
use core\cls\db as db;

class module{
	use view;
	use addons;
	
	/*
	 * construct
	 */
	function __construct(){}
	
	//this function return back menus for use in admin area
	public static function coreMenu(){
		$menu = array();
		$url = core\general::createUrl(['service','administrator','load','payment','transactions']);
		array_push($menu,[$url, _('Transactions')]);
		$url = core\general::createUrl(['service','administrator','load','payment','settings']);
		array_push($menu,[$url, _('settings')]);
		$ret = [];
		array_push($ret, ['<span class="glyphicon glyphicon-usd" aria-hidden="true"></span>' , _('payment')]);
		array_push($ret,$menu);
		return $ret;
	}
    
    /**
     * show info of payment and select bank for pay
     * @return array [title,body]
     */
    protected function moduleShowInfo(){
       $orm = db\orm::singleton();
        $options = explode('/',PLUGIN_OPTIONS);
        if(count($options) == 3)
       if($orm->count('payment_transactions','sid=?',[$options[2]]) != 0){
           $transaction =$orm->findOne('payment_transactions','sid=?',[$options[2]]);
           $transaction->plugin = $options[0];
           $transaction->action = $options[1];
           $orm->store($transaction);
           //load active lib
           $actives = $orm->find('payment_actives','enable=1');
           return $this->viewShowInfo($transaction,$actives);
       }
       return browser\msg::pageError();
    }

    /**
     * show successfull payment message
     * @return array [title,body]
     */
    protected function moduleSuccessMsg(){
        $orm = db\orm::singleton();
        if($orm->count('payment_transactions','sid=?',[PLUGIN_OPTIONS]) != 0)
            return $this->viewSuccessMsg($orm->findOne('payment_transactions','sid=?',[PLUGIN_OPTIONS]));
        return browser\msg::pageNotFound();
    }

    /**
     * function for edit system settings
     * @return array [title,body]
     */
    protected function moduleSettings(){
        $registry = core\registry::singleton();
        return $this->viewSettings($registry->get('payment','paylineApi'));
    }

    /**
     * function for show all transactions
     * @return array [title,body]
     */
    protected function moduleTransactions(){
        $orm = db\orm::singleton();
        return $this->viewTransactions($orm->findAll('payment_transactions'));
    }
	
}
