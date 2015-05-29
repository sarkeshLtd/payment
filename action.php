<?php
namespace addon\plugin\payment;
use \core\cls\core as core;
use \core\cls\browser as browser;


class action extends module{
	use view;
	/*
	 * construct
	 */
	function __construct(){
		parent::__construct();
	}
    /**
     * show info of payment and select bank for pay
     * @return array [title,body]
     */
    public function showInfo(){
        if(defined('PLUGIN_OPTIONS'))
            return $this->moduleShowInfo();
        return browser\msg::pageNotFound();
    }

    /**
     * show successfull payment message
     * @return array [title,body]
     */
    public function successMsg(){
        if(defined('PLUGIN_OPTIONS'))
            return $this->moduleSuccessMsg();
        return browser\msg::pageNotFound();
    }

    /**
     * function for edit system settings
     * @return array [title,body]
     */
    public function settings(){
        if($this->isLogedin() && $this->hasAdminPanel())
            return $this->moduleSettings();
        return browser\msg::pageAccessDenied();
    }

    /**
     * function for show all transactions
     * @return array [title,body]
     */
    public function transactions(){
        if($this->isLogedin() && $this->hasAdminPanel())
            return $this->moduleTransactions();
        return browser\msg::pageAccessDenied();
    }

    /**
     * show fail message in payment system
     * @return array [title,body]
     */
    public function failPaymentMsg(){
        return $this->viewFailPaymentMsg();
    }
}
