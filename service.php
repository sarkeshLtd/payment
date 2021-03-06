<?php
namespace addon\plugin\payment;
use \core\cls\core as core;
use \core\cls\browser as browser;
use \core\cls\db as db;

class service extends module{
	
	function __construct(){}
    
     /**
     * jump to accepter site
     * @return empty string
     */
     public function jump(){
        if(defined('PLUGIN_OPTIONS')){
            $orm = db\orm::singleton();
            if($orm->count('payment_transactions','sid=?',[PLUGIN_OPTIONS]) != 0){
                $transaction = $orm->findOne('payment_transactions','sid=?',[PLUGIN_OPTIONS]);
                if($transaction->state == 0){
                    $registry = core\registry::singleton();
                    $api = $registry->get('payment','paylineApi');
                    $amount = $transaction->amount;
                    $redirect = urlencode(core\general::createUrl(['service','payment','checkPayment',PLUGIN_OPTIONS]));
                    $result = \payline\payline::send($api,$amount,$redirect);
                    if($result > 0){
                        $go = \payline\payline::paymentUrl($result);
                        header("Location: $go");
                        return $result;
                    }
                    return browser\page::simplePage(_('Error in payment system'),_('an error has occurred in payment system.we are try to fix that'),3,true);
                }
                //transaction was pay before
                return browser\page::simplePage(_('Payment Error!'),_('This transaction Was paid before.') . '<a href="' . SiteDomain . '" ><hr>' . _('Home page') . '</a>',4,true);
            }
        }
     }
     
     /**
      * check for payment is OK
      * @return string, mybe null
      */
    public function checkPayment(){
        if(defined('PLUGIN_OPTIONS') && isset($_POST['trans_id']) && isset($_POST['id_get'])){
            $registry = core\registry::singleton();
            $api = $registry->get('payment','paylineApi');
            $trans_id = $_POST['trans_id'];
            $id_get = $_POST['id_get'];
            $result =\payline\payline::get($api,$trans_id,$id_get);
            if($result == 1) {
                //payment was successful;
                $orm = db\orm::singleton();
                if ($orm->count('payment_transactions', 'sid=?', [PLUGIN_OPTIONS]) != 0){
                    $transaction = $orm->findOne('payment_transactions', 'sid=?', [PLUGIN_OPTIONS]);
                    $transaction->date = time();
                    $transaction->trans_id = $trans_id;
                    $transaction->id_get = $id_get;
                    $transaction->state = 1;
                    $orm->store($transaction);
                    //run plugin module function
                    if(method_exists('\\addon\\plugin\\' . $transaction->plugin . '\\module',$transaction->action))
                        call_user_func(array('\\addon\\plugin\\' . $transaction->plugin . '\\module',$transaction->action),$transaction->sid);
                }
                //jump to show succesfull message
                return core\router::jump(['payment','successMsg',$transaction->sid]);
            }
        }
        //jump to error page
        return core\router::jump(['payment','failPaymentMsg']);
    }
}
