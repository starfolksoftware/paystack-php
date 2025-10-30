<?php

namespace StarfolkSoftware\Paystack\Options\Terminal;

use StarfolkSoftware\Paystack\Abstracts\OptionsAbstract;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateOptions extends OptionsAbstract
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
            ->info('Name of the terminal');

        $resolver->define('address')
            ->allowedTypes('string')
            ->info('The address of the Terminal');
    }
}