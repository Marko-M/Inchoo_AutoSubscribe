<?php
/**
* Inchoo
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@magentocommerce.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Please do not edit or add to this file if you wish to upgrade
* Magento or this extension to newer versions in the future.
* Inchoo developers (Inchooer's) give their best to conform to
* "non-obtrusive, best Magento practices" style of coding.
* However, Inchoo does not guarantee functional accuracy of
* specific extension behavior. Additionally we take no responsibility
* for any possible issue(s) resulting from extension usage.
* We reserve the full right not to provide any kind of support for our free extensions.
* Thank you for your understanding.
*
* @category Inchoo
* @package AutoSubscribe
* @author Marko Martinović <marko.martinovic@inchoo.net>
* @copyright Copyright (c) Inchoo (http://inchoo.net/)
* @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
*/

class Inchoo_AutoSubscribe_Model_Observer extends Varien_Object
{
    public function salesOrderPlaceAfter($observer)
    {        
    	$email = $observer->getEvent()->getOrder()->getCustomerEmail();
        
        Mage::log('salesOrderPlaceAfter: '.$email);
        
        $this->_autoSubscribe($email);
    }
    
    public function customerRegisterSuccess($observer)
    {                
        $email = $observer->getEvent()->getCustomer()->getEmail();
        
        Mage::log('customerRegisterSuccess: '.$email);
        
        $this->_autoSubscribe($email);
    }
    
    protected function _autoSubscribe($email)
    {
        Mage::log('_autoSubscribe: '.$email);
        
        $subscriber = Mage::getModel('newsletter/subscriber')->loadByEmail($email);
        if($subscriber->getStatus() != Mage_Newsletter_Model_Subscriber::STATUS_SUBSCRIBED &&
                $subscriber->getStatus() != Mage_Newsletter_Model_Subscriber::STATUS_UNSUBSCRIBED) {
            $subscriber->setImportMode(true)->subscribe($email);
        }
    }    
}