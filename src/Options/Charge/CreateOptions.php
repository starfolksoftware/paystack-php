<?php

namespace StarfolkSoftware\Paystack\Options\Charge;

use StarfolkSoftware\Paystack\Abstracts\OptionsAbstract;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateOptions extends OptionsAbstract
{
    /**
     * Set defaults, allowed types and values of the options.
     * 
     * @param OptionsResolver $resolver
     * 
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->define('email')
            ->required()
            ->allowedTypes('string')
            ->info('Customer\'s email address');

        $resolver->define('amount')
            ->required()
            ->allowedTypes('int')
            ->info('Amount should be in kobo if currency is NGN, pesewas, if currency is GHS, and cents, if currency is ZAR');

        $resolver->define('bank')
            ->allowedTypes('array')
            ->info('Bank account to charge (don\'t send if charging an authorization code)');

        $resolver->define('authorization_code')
            ->allowedTypes('string')
            ->info('An authorization code to charge (don\'t send if charging a bank account)');

        $resolver->define('pin')
            ->allowedTypes('string')
            ->info('4-digit PIN (send with a non-reusable authorization code)');

        $resolver->define('metadata')
            ->allowedTypes('array')
            ->info('A JSON object');

        $resolver->define('reference')
            ->allowedTypes('string')
            ->info('Unique transaction reference. Only -, ., = and alphanumeric characters allowed.');

        $resolver->define('ussd')
            ->allowedTypes('array')
            ->info('USSD type to charge (don\'t send if charging an authorization code, bank or card)');

        $resolver->define('mobile_money')
            ->allowedTypes('array')
            ->info('Mobile money details (don\'t send if charging an authorization code, bank or card)');

        $resolver->define('device_id')
            ->allowedTypes('string')
            ->info('This is the unique identifier of the device a user uses in making payment');
    }
}