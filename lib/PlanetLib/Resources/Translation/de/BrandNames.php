<?php
namespace PlanetLib\Resources\Translation\de;
use PlanetLib\Resources\Translation\BrandNames as BrandNamesAbstract;


/**
 * Class BrandNames
 * @package PlanetLib\Resources\Translation\de
 */
class BrandNames extends BrandNamesAbstract
{
    protected $brandNames = array (
        'AMEX'                    => array(
            'name' => 'American Express',
        ),
        'BARPAY'                  => array(
            'name' => 'BarPay',
        ),
        'BOLETO'                  => array(
            'name' => 'Boleto',
        ),
        'CARTEBLEUE'              => array(
            'name' => 'Carte Bleue',
        ),
        'CHINAUNIONPAY'           => array(
            'name' => 'China UnionPay',
        ),
        'DANKORT'                 => array(
            'name' => 'Dankort',
        ),
        'DINERS'                  => array(
            'name' => 'Diners',
        ),
        'DIRECTDEBIT_SEPA'        => array(
            'name' => 'SEPA Überweisung',
        ),
        'DIRECTDEBIT_SEPA_MIX_DE' => array(
            'name' => 'Direct Debit Germany and Sepa',
        ),
        'DIRECTDEBIT_SEPA_MIX_AT' => array(
            'name' => 'Direct Debit Austria and Sepa',
        ),
        'DIRECTDEBIT_DE'          => array(
            'name' => 'Direct Debit Germany',
        ),
        'DIRECTDEBIT_AT'          => array(
            'name' => 'Direct Debit Austria',
        ),
        'DISCOVER'                => array(
            'name' => 'Discover',
        ),
        'GIROPAY'                 => array(
            'name' => 'Giropay',
        ),
        'IDEAL'                   => array(
            'name' => 'iDeal',
        ),
        'INVOICE'                 => array(
            'name' => 'Rechnung',
        ),
        'JCB'                     => array(
            'name' => 'JCB',
        ),
        'MAESTRO'                 => array(
            'name' => 'Maestro',
        ),
        'MASTER'                  => array(
            'name' => 'MasterCard',
        ),
        'PASTEANDPAY_V'           => array(
            'name' => 'PasteAndPay',
        ),
        'PAYPAL'                  => array(
            'name' => 'PayPal',
        ),
        'POSTEPAY'                => array(
            'name' => 'PostePay',
        ),
        'SOFORTUEBERWEISUNG'      => array(
            'name' => 'sofort Überweisung',
        ),
        'UKASH'                   => array(
            'name' => 'Ukash',
        ),
        'VISA'                    => array(
            'name' => 'Visa',
        ),
        'VISADEBIT'               => array(
            'name' => 'Visa Debit',
        ),
        'VISAELECTRON'            => array(
            'name' => 'Visa Electron',
        ),
        'VPAY'                    => array(
            'name' => 'V PAY',
        ),
        'VSTATION_V'              => array(
            'name' => 'Voucher Station',
        ),
        'AXESS'                   => array(
            'name' => 'AXESS',
        ),
        'BONUS'                   => array(
            'name' => 'Bonus',
        ),
        'MAXIMUM'                 => array(
            'name' => 'Maximum',
        ),
        'WORLD'                   => array(
            'name' => 'World',
        ),
        'CARDFINANS'              => array(
            'name' => 'CardFinans',
        ),
        'ADVANTAGE'               => array(
            'name' => 'Advantage',
        ),
        'QOOQO'                   => array(
            'name' => 'Qooqo',
        ),
        'KLARNA_INVOICE'          => array(
            'name' => 'Klarna Invoice',
        ),
        'KLARNA_INSTALLMENTS'     => array(
            'name' => 'Klarna Installments',
        ),
        'ASYACARD'                => array(
            'name' => 'AsyaCard',
        )
    );

    /**
     * @return array
     */
    protected function _getBrandNames()
    {
        $brandNames = array();
        foreach ($this->brandNames as $brandCode => $brand) {
            $brandNames[$brandCode] = $brand['name'];
        }
        return $brandNames;
    }


    /**
     * @param $key
     * @return null|string
     */
    protected function _getBrandName($key)
    {
        if (isset($this->brandNames[$key])) {
            return $this->brandNames[$key]['name'];
        }
        else {
            return null;
        }
    }

}