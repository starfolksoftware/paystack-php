<?php

namespace StarfolkSoftware\Paystack\Options\Split;

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
        $resolver->define('name')
            ->allowedTypes('string')
            ->info('The name of the split');

        $resolver->define('active')
            ->allowedTypes('bool')
            ->info('Any of true or false');

        $resolver->define('sort_by')
            ->allowedTypes('string')
            ->info('Sort by name, defaults to createdAt date');

        $resolver->define('perPage')
            ->allowedTypes('int')
            ->info('Number of splits per page. If not specified, we use a default value of 50.');

        $resolver->define('page')
            ->allowedTypes('int')
            ->info('Page number to view. If not specified, we use a default value of 1.');

        $resolver->define('from')
            ->allowedTypes('string')
            ->info('A timestamp from which to start listing splits e.g. 2019-09-24T00:00:05.000Z, 2019-09-21');

        $resolver->define('to')
            ->allowedTypes('string')
            ->info('A timestamp at which to stop listing splits e.g. 2019-09-24T00:00:05.000Z, 2019-09-21');
    }
}