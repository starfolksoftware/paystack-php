<?php

namespace StarfolkSoftware\Paystack\Options\Refund;

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
        $resolver->define('reference')
            ->allowedTypes('string')
            ->info('Identifier for transaction to be refunded');

        $resolver->define('currency')
            ->allowedTypes('string')
            ->info('Three-letter ISO currency. Allowed values are: NGN, GHS, ZAR or USD');

        $resolver->define('from')
            ->allowedTypes('string')
            ->info('A timestamp from which to start listing refunds');

        $resolver->define('to')
            ->allowedTypes('string')
            ->info('A timestamp at which to stop listing refunds');

        $resolver->define('perPage')
            ->allowedTypes('int')
            ->info('Number of records to fetch per page');

        $resolver->define('page')
            ->allowedTypes('int')
            ->info('The section to retrieve');
    }
}