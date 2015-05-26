<?php
namespace addon\plugin\payment;
use \core\cls\core as core;
use \core\cls\db as db;
class setup{

	/*
	 * function for setup plugin
	 */
	public function install(){
        $orm = db\orm::singleton();
        $query = "CREATE TABLE IF NOT EXISTS `payment_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sid` varchar(32) NOT NULL,
  `plugin` varchar(20) NOT NULL,
  `action` varchar(20) NOT NULL,
  `amount` varchar(100) NOT NULL,
  `owner` varchar(50) NOT NULL,
  `state` varchar(5) NOT NULL, 
  `date` varchar(12) NOT NULL,
   `trans_id` varchar(12) NOT NULL,
   `id_get` varchar(12) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
        @$orm->exec($query,[],NON_SELECT);
        
        $query = "CREATE TABLE IF NOT EXISTS `payment_actives` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `enable` varchar(20) NOT NULL, 
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
        @$orm->exec($query,[],NON_SELECT);

        //save registry keys
        $registry =  core\registry::singleton();
        $registry->newKey('payment','paylineApi','0');

    }
}