<?php

namespace StarfolkSoftware\Paystack\Options\Terminal;

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
            ->info('Specify how many records you want to retrieve per page. If not specified, we use a default value of 50.');

        $resolver->define('next')
            ->allowedTypes('string')
            ->info('A cursor that indicates your place in the list. It can be used to fetch the next page of the list');

        $resolver->define('previous')
            ->allowedTypes('string')
            ->info('A cursor that indicates your place in the list. It should be used to fetch the previous page of the list after an intial next request');
    }
}