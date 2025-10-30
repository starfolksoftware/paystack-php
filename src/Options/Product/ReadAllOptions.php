<?php

namespace StarfolkSoftware\Paystack\Options\Product;

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
        $resolver->define('perPage')
            ->allowedTypes('int')
            ->info('Number of records to fetch per page');

        $resolver->define('page')
            ->allowedTypes('int')
            ->info('The section to retrieve');

        $resolver->define('from')
            ->allowedTypes('string')
            ->info('A timestamp from which to start listing products');

        $resolver->define('to')
            ->allowedTypes('string')
            ->info('A timestamp at which to stop listing products');
    }
}