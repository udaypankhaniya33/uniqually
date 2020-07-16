<?php

use \Illuminate\Support\Facades\Config;

if (! function_exists('getPaymentOccurrenceById')) {
    function getPaymentOccurrenceById($paymentOccurenceId)
    {
        foreach (Config::get('constances.payment_occurrences') as $index => $paymentOccurenceType){
            if ($paymentOccurenceType==$paymentOccurenceId) return $index;
        }
    }
}

if (! function_exists('getPaymentOccurrenceByString')) {
    function getPaymentOccurrenceByString($paymentOccurenceIdString)
    {
        foreach (Config::get('constances.payment_occurrences') as $index => $paymentOccurenceType){
            if ($paymentOccurenceIdString==$index) return $paymentOccurenceType;
        }
    }
}