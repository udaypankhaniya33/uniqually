<?php

/**
 * This configuration file holds global constance variables for the centralized API
 * ---------------------------------------------------------------------------------------------------------------------
 */

return [

    /*
     * Allowed user types in vManageTax platform
     * Note :: This array structure may change with future developments
     * -----------------------------------------------------------------------------------------------------------------
     * */
    'user_types' => [
        'ADMIN'     => 1,
        'CUSTOMER'  => 2,
        'OM'        => 3,
        'EXPERT'    => 4,
    ],

    'payment_occurrences' => [
        'IS_ONE_TIME' => 1,
        'IS_MONTHLY' => 2,
        'IS_ANNUAL' => 3,
    ]

];
