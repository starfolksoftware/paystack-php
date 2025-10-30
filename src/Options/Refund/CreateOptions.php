<?php

namespace StarfolkSoftware\Paystack\Options\Refund;

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
        $resolver->define('transaction')
            ->required()
            ->allowedTypes('string')
            ->info('Transaction reference or id');

        $resolver->define('amount')
            ->allowedTypes('int')
            ->info('Amount ( in kobo if currency is NGN, pesewas, if currency is GHS, and cents, if currency is ZAR ) to be refunded to the customer. Amount is optional(defaults to original transaction amount) and cannot be more than the original transaction amount.');

        $resolver->define('currency')
            ->allowedTypes('string')
            ->info('Three-letter ISO currency. Allowed values are: NGN, GHS, ZAR or USD');

        $resolver->define('customer_note')
            ->allowedTypes('string')
            ->info('Customer reason');

        $resolver->define('merchant_note')
            ->allowedTypes('string')
            ->info('Merchant reason');
    }
}