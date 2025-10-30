<?php

namespace StarfolkSoftware\Paystack\Options\DedicatedVirtualAccount;

use StarfolkSoftware\Paystack\Abstracts\OptionsAbstract;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReadAllOptions extends OptionsAbstract
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
        $resolver->define('active')
            ->allowedTypes('bool')
            ->info('Status of the dedicated virtual account');

        $resolver->define('currency')
            ->allowedTypes('string')
            ->info('The currency of the dedicated virtual account. Only NGN is currently allowed');

        $resolver->define('provider_slug')
            ->allowedTypes('string')
            ->info('The bank\'s slug in lowercase, without spaces e.g. wema-bank');

        $resolver->define('bank_id')
            ->allowedTypes('string')
            ->info('The bank\'s ID e.g. 035');

        $resolver->define('customer')
            ->allowedTypes('string')
            ->info('The customer\'s ID');
    }
}