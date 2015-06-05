<?php
namespace addon\plugin\payment;
use \core\cls\core as core;
use \core\cls\network as network;
use \core\cls\db as db;

trait addons {
	use \core\plugin\administrator\addons;
    
    /*
     * start new payment operation with this function
     * @param string $plugin, plugin name
     * @param string $action , action for proccess result of operation
     * @param string $amount, amount of transaction
     * @param string $amount, owner of transaction
     * return string transaction payment id
     */
    public function newPayment($plugin,$action,$amount,$owner){
        $orm = db\orm::singleton();
        $transaction = $orm->dispense('payment_transactions');
        $transaction->plugin = $plugin;
        $transaction->action = $action;
        $transaction->amount = $amount;
        $transaction->owner = $owner;
        $transaction->date = 0;
        $transaction->sid = core\general::randomString(32,'NC');
        $transaction->state = 0;
        $orm->store($transaction);
        return $transaction->sid;
    }

}
