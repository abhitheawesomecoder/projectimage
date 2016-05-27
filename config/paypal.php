<?php

return array(
    // set your paypal credential
    'client_id' => 'AVTpJlDRdzaXGKq6zlCCNciNGeypRXMG8eFghE9InAOCVsy1sz24Cxw5StR2J4bjSZ_0_-XQIHk-_4ze',
    'secret' => 'EDyA50SLU58KQgMxmOutF8fAMIGFW0NN6tGwLwBSn459qVa_pRhHSNaPg3KDrCTK06DWnsoLctsJKQNr',

    /**
     * SDK configuration 
     */
    'settings' => array(
        /**
         * Service Name for upgrade account
         */
        'service' => 'Premium Account',

        /**
         * Service Description for upgrade account
         */
        'description' => 'ImageMarker Premium Account',

        /**
         * Price of first package for upgrade account
         */
        'price' => '29',

        /**
         * Price of second package for upgrade account
         */
        'price2' => '39',

        /**
         * Currency for upgrade account
         */
        'currency' => 'EUR',

        /**
         * Available option 'sandbox' or 'live'
         */
        'mode' => 'live',

        /**
         * Specify the max request time in seconds
         */
        'http.ConnectionTimeOut' => 30,

        /**
         * Whether want to log to a file
         */
        'log.LogEnabled' => true,

        /**
         * Specify the file that want to write on
         */
        'log.FileName' => storage_path() . '/logs/paypal.log',

        /**
         * Available option 'FINE', 'INFO', 'WARN' or 'ERROR'
         *
         * Logging is most verbose in the 'FINE' level and decreases as you
         * proceed towards ERROR
         */
        'log.LogLevel' => 'FINE'
    ),
);

?>