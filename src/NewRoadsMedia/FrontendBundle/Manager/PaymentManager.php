<?php

namespace NewRoadsMedia\FrontendBundle\Manager;

use Doctrine\ORM\EntityManager;
use NewRoadsMedia\FrontendBundle\Doctrine\ObjectManager;
use NewRoadsMedia\FrontendBundle\Entity\Payment;
use Psr\Log\LoggerInterface;

/**
 * @method \NewRoadsMedia\FrontendBundle\Entity\Payment create()
 * @method \NewRoadsMedia\FrontendBundle\Entity\Payment find($id)
 * @method \NewRoadsMedia\FrontendBundle\Entity\Payment getReference($id)
 */
class PaymentManager extends ObjectManager
{
    protected $authorizeNetTestMode;

    /** @var LoggerInterface */
    protected $logger;

    /** @var string */
    protected $siteTitle;

    /** @var string */
    protected $login;

    /** @var string */
    protected $transactionKey;

    public function __construct(EntityManager $entityManager, $class, $authorizeNetTestMode, $logger, $siteTitle, $login, $transactionKey)
    {
        parent::__construct($entityManager, $class);
        $this->authorizeNetTestMode = $authorizeNetTestMode;
        $this->logger = $logger;
        $this->siteTitle = $siteTitle;
        $this->login = $login;
        $this->transactionKey = $transactionKey;
    }

    /**
     * @param Payment $payment
     * @param $company
     * @param $cvv
     * @return array
     */
    public function chargeCreditCard(Payment $payment, $cvv, $company = null)
    {
        $firstName = $payment->getNameOnCard();
        if ($company) {
            $firstName .= ' - ' . $company;
        }
        $testMode = $this->authorizeNetTestMode;
        $amount = $testMode ? $cvv : $payment->getChargeAmount();
        $authorize_parameters = array(
            'x_login' => $this->login,
            'x_version' => '3.1',
            'x_tran_key' => $this->transactionKey,
            'x_method' => 'CC',
            'x_invoice_num' => '',
            'x_description' => $this->siteTitle . ' Transaction',
            'x_first_name' => $firstName,
            'x_type' => 'AUTH_CAPTURE',
            'x_amount' => $amount,
            'x_delim_data' => 'TRUE',
            'x_delim_char' => '|',
            'x_relay_response' => 'FALSE',
            'x_card_num' => $payment->getCreditCardNumber(),
            'x_card_code' => $cvv,
            'x_exp_date' => $payment->getExpirationDateMonth() . $payment->getExpirationDateYear(),
            'x_test_request' => $testMode,
            'x_duplicate_window' => 1,
        );

        //url-ify the data for the POST
        $fields_string = '';
        foreach ($authorize_parameters as $key=>$value) {
            $fields_string .= $key.'='. urlencode($value).'&';
        }
        rtrim($fields_string, '&');

        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, 'https://secure.authorize.net/gateway/transact.dll');
        curl_setopt($ch, CURLOPT_POST, count($authorize_parameters));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER ,true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        //execute post
        $result = curl_exec($ch);
        $this->logger->info('Sent: ' . $fields_string);
        $this->logger->info('Received: ' . $result);
        $error = curl_error($ch);
        if ($error) {
            $this->logger->info('Error: ' . $error);
        }

        //close connection
        curl_close($ch);

        $norm = array(
            'response_code' => 0,
            'response_msg' => 'There was a problem processing your credit card.',
            'response_sub_code' => 0,
            'response_reason_code' => 0,
            'authorization' => null,
            'avs' => null,
            'cvv' => null,
            'ccnum' => null,
            'trans_id' => null,
        );

        if ($result) {
            $response = explode('|', $result);
            if ($response) {
                $norm['response_code'] = $response[0];
                $norm['response_sub_code'] = $response[1];
                $norm['response_reason_code'] = $response[2];
                $norm['response_msg'] = $response[3];
                $norm['authorization'] = $response[4];
                $norm['avs'] = $response[5];
                $norm['cvv'] = $response[38];
                $norm['ccnum'] = substr($response[50], -4);
                $norm['trans_id'] = $response[6];
            }

            $payment->setComment(sprintf('Response Code: %d' . PHP_EOL . 'AuthCode: %d'
                , $norm['response_code']
                , $norm['authorization']
            ));
            $payment->setAuthorizationCode($norm['authorization']);
            $payment->setTransactionId($norm['trans_id']);

            if ($norm['response_code'] == 1) {
                $payment->setApproval(true);
            }
        }

        return $norm;
    }
}