<?php
namespace addon\plugin\payment;
use \core\control as control;
use \core\cls\core as core;

trait view {
	
	/**
     * show info of payment and select bank for pay
     * @param object $transaction, transaction info
     * @param array $activeClasses, active library for do payment
     * @return array [title,body]
     */
    protected function viewShowInfo($transaction,$activeClasses){
       $form = new control\form('paymentShowInfo');
       
       $imgLogo = new control\image('imgLogo');
       $imgLogo->src = SiteDomain . '/plugins/defined/payment/images/shopping_bag.png';
       
       $label = new control\label(sprintf(_('Name:%s'),$transaction->owner));
       $row = new control\row;
       $row->in_table = false;
       $row->add($imgLogo,2);
       $form->add($label);
       $lblAmount = new control\label(sprintf(_('The amount payable:%s Rials'),$transaction->amount));
       $form->add($lblAmount);
       //MULTI PORTS FOR PAYMENT NOT DEVELOPED YET JUST ACCEPT PAYLINE
       $rowPay = new control\row;
       $rowPay->in_table = false;
       $label = new control\label(_('Your operation will do with this accepter:'));
       $rowPay->add($label,5);
       $imgPayline = new control\image('imgLogo');
       $imgPayline->src = SiteDomain . '/plugins/defined/payment/images/payline-logo.png';
       $imgPayline->href = "http://http://www.payline.ir/";
       $rowPay->add($imgPayline,7);
       $form->add($rowPay);
       
       $btnPayment = new control\button('btnPayment');
       $btnPayment->configure('LABEL',_('Yes, pay it'));
       $btnPayment->configure('TYPE','primary');
       $btnPayment->href = core\general::createUrl(['service','payment','jump',$transaction->sid]);
       $btn_cancel = new control\button('btn_cancel');
       $btn_cancel->configure('LABEL',_('Cancel'));
       $btn_cancel->configure('HREF',SiteDomain);
       $rowButtons = new control\row;
       $rowButtons->configure('IN_TABLE',false);

       $rowButtons->add($btnPayment,2);
       $rowButtons->add($btn_cancel,10);
       $form->add($rowButtons);
       $row->add($form,10);
       return [_('Show payment info'),$row->draw()];
    }

    /**
     * show successfull payment message
     * @param object $transaction, transaction information
     * @return array [title,body]
     */
    protected function viewSuccessMsg($transaction){
        $form = new control\form('paymentShowInfo');

        $imgLogo = new control\image('imgLogo');
        $imgLogo->src = SiteDomain . '/plugins/defined/payment/images/success.png';

        $label = new control\label(sprintf(_('Name:%s'),$transaction->owner));
        $row = new control\row;
        $row->in_table = false;
        $row->add($imgLogo,2);
        $form->add($label);
        $lblAmount = new control\label(sprintf(_('Amount paid:%s Rials'),$transaction->amount));
        $form->add($lblAmount);
        $lblSer = new control\label(sprintf(_('serial:%s'),$transaction->sid));
        $form->add($lblSer);
        $lblTrack = new control\label(sprintf(_('Tracking Code:%s'),$transaction->id));
        $form->add($lblTrack);
        //home button
        $btnHome = new control\button('btnHome');
        $btnHome->type = 'success';
        $btnHome->size = 'lg';
        $btnHome->label = _('Jump to home page');
        $btnHome->href = SiteDomain;
        $form->add($btnHome);
        $row->add($form,10);
        return [_('Payment was successful'),$row->draw()];
    }

    /**
     * function for edit system settings
     * @param string $paylineApi, payline api key
     * @return array [title,body]
     */
    protected function viewSettings($paylineApi){
        $form = new control\form('paymentSettingsForm');
        $txtPaylineApi = new control\textbox('txtPaylineApi');
        $txtPaylineApi->label = _('Payline Api:');
        $txtPaylineApi->help = sprintf(_('For get your api visit %s website.'),'<a href="http://www.payline.ir/" >' . _('Payline') . '</a>');
        $txtPaylineApi->value = $paylineApi;
        $form->add($txtPaylineApi);

        $btnEdite = new control\button('btnEdite');
        $btnEdite->configure('LABEL',_('Save Settings'));
        $btnEdite->configure('TYPE','primary');
        $btnEdite->p_onclick_plugin = 'payment';
        $btnEdite->p_onclick_function = 'btnOnclickSaveSettings';

        $btn_cancel = new control\button('btn_cancel');
        $btn_cancel->configure('LABEL',_('Cancel'));
        $btn_cancel->configure('HREF',core\general::createUrl(['service','administrator','load','administrator','dashboard']));

        $row = new control\row;
        $row->configure('IN_TABLE',false);

        $row->add($btnEdite,2);
        $row->add($btn_cancel,10);
        $form->add($row);
        return [_('Payment settings'),$form->draw()];
    }

    /**
     * function for show all transactions
     * @param array||null $trans
     * @return array [title,body]
     */
    protected function viewTransactions($trans){
        $form = new control\form('payment_form_transactions');
        $table = new control\table('tblpaymentTransactions');
        $table->configure('HEADERS', [_('ID'), _('Name'), _('Localize'), _('Edit'), _('Delete')]);
        $table->configure('HEADERS_WIDTH', [1, 7, 2, 1, 1]);
        $table->configure('ALIGN_CENTER', [TRUE, FALSE, TRUE, TRUE, TRUE]);
        $table->configure('BORDER', true);
        $table->configure('SIZE', 9);
        $counter = 0;
        if(!is_null($trans)){
            foreach($trans as $tr){

                $counter += 1;
                $row = new control\row('blog_cat_row');

                $lbl_id = new control\label('lbl');
                $lbl_id->configure('LABEL', $counter);
                $row->add($lbl_id, 1);

                $lbl_cat = new control\label('lbl');
                $lbl_cat->configure('LABEL', 0);
                $row->add($lbl_cat, 1);

                $lbl_cat = new control\label('lbl');
                $lbl_cat->configure('LABEL', 0);
                $row->add($lbl_cat, 1);

                $btnVewTransaction = new control\button('btnVewTransaction');
                $btnVewTransaction->configure('LABEL', _('View'));
                $btnVewTransaction->configure('HREF', core\general::createUrl(['service', 'administrator', 'load', 'payment', 'viewTransaction', $tr->id]));
                $row->add($btnVewTransaction, 2);

                $table->add_row($row);
            }
        }
        $form->add($table);
        return [_('Transactions'),$form->draw()];
    }

    /**
     * show fail message in payment system
     * @return array [title,body]
     */
    public function viewFailPaymentMsg(){
        $form = new control\form('frmPaymentFailMsgPayment');
        $label = new control\label(_('Your are canceled your payment process or we have an error in payment system.'));
        $label->type = 'warning';
        $form->add($label);

        $btnHome = new control\button('frmBtn');
        $btnHome->label = _('Home');
        $btnHome->type = 'primary';
        $btnHome->href = SiteDomain;
        $form ->add($btnHome);
        return [_('Fail in payment'),$form->draw()];
    }
}
