<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Options\DirectDebit;

use StarfolkSoftware\Paystack\Abstracts\OptionsAbstract;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TriggerActivationChargeOptions extends OptionsAbstract
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
        $resolver->define('authorization_code')
            ->allowedTypes('string')
            ->info('Authorization code of the authorization that was returned to you following successful payment');

        $resolver->define('amount')
            ->required()
            ->allowedTypes('int')
            ->info('Amount that should be charged (kobo, kobo, pesewas, or cents)');

        $resolver->define('currency')
            ->allowedTypes('string')
            ->allowedValues(['NGN', 'GHS', 'ZAR', 'USD'])
            ->info('The currency in which to charge. Allowed values are: NGN, GHS, ZAR or USD');

        $resolver->define('email')
            ->allowedTypes('string')
            ->info('Customer\'s email address (used when insufficient information from authorization code)');

        $resolver->define('reference')
            ->allowedTypes('string')
            ->info('Unique transaction reference');

        $resolver->define('mandate_code')
            ->allowedTypes('string')
            ->info('Mandate code for direct debit');
    }
}