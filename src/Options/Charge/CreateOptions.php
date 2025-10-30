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
            ->allowedTypes('string')
            ->info('Amount in subunit of the supported currency');

        $resolver->define('split_code')
            ->allowedTypes('string')
            ->info('The split code of a previously created split. e.g. SPL_98WF13Eb3w');

        $resolver->define('subaccount')
            ->allowedTypes('string')
            ->info('The code for the subaccount that owns the payment. e.g. ACCT_8f4s1eq7ml6rlzj');

        $resolver->define('transaction_charge')
            ->allowedTypes('int')
            ->info('An amount used to override the split configuration for a single split payment');

        $resolver->define('bearer')
            ->allowedTypes('string')
            ->allowedValues('account', 'subaccount')
            ->info('Use this param to indicate who bears the transaction charges. Defaults to account');

        $resolver->define('bank')
            ->allowedTypes('array')
            ->info('Bank account to charge (don\'t send if charging an authorization code)');

        $resolver->define('card')
            ->allowedTypes('array')
            ->info('Card details to charge (don\'t send if charging an authorization code)');

        $resolver->define('bank_transfer')
            ->allowedTypes('array')
            ->info('Takes the settings for the Pay with Transfer (PwT) channel');

        $resolver->define('ussd')
            ->allowedTypes('array')
            ->info('USSD type to charge (don\'t send if charging an authorization code, bank or card)');

        $resolver->define('mobile_money')
            ->allowedTypes('array')
            ->info('Mobile money details (don\'t send if charging an authorization code, bank or card)');

        $resolver->define('qr')
            ->allowedTypes('array')
            ->info('Takes a provider parameter with the value set to: scan-to-pay');

        $resolver->define('authorization_code')
            ->allowedTypes('string')
            ->info('An authorization code to charge (don\'t send if charging a bank account)');

        $resolver->define('pin')
            ->allowedTypes('string')
            ->info('4-digit PIN (send with a non-reusable authorization code)');

        $resolver->define('metadata')
            ->allowedTypes('array')
            ->info('Used for passing additional details for your post-payment processes');

        $resolver->define('reference')
            ->allowedTypes('string')
            ->info('Unique transaction reference. Only -, ., = and alphanumeric characters allowed');

        $resolver->define('device_id')
            ->allowedTypes('string')
            ->info('This is the unique identifier of the device a user uses in making payment');
    }
}