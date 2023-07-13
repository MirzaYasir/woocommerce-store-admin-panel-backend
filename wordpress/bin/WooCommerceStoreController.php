<?php
require dirname(__DIR__) . '/vendor/autoload.php';

use Automattic\WooCommerce\Client;

class WooCommerceStoreController
{
    private static $instance;
    private $consumer_key;
    private $consumer_secret;
    public $woocommerce;

    private function __construct()
    {
        $this->consumer_key = 'ck_5dce680c19cbcb38b159744b1433599d501c510c';
        $this->consumer_secret = 'cs_506a62c66034096d5a9f11e5a5a044ba45f0c61c';
        $this->woocommerce = new Client(
            'http://localhost/thailand/wordpress/',
            $this->consumer_key,
            $this->consumer_secret,
            [
                'version' => 'wc/v3',
            ]
        );
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new WooCommerceStoreController();
        }
        return self::$instance;
    }

    public function getRequest($endpoint, $params) {
        $response = $this->woocommerce->get($endpoint, $params);
        echo "\n------------".$endpoint." Response------------\n";
        print_r($response);
        echo "\n------------".$endpoint." Response------------\n";
        return $response;
    }

    public function postRequest($url, $params) {

    }
}
