<?php

namespace StarfolkSoftware\Paystack\Options\Terminal;

use StarfolkSoftware\Paystack\Abstracts\OptionsAbstract;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DecommissionOptions extends OptionsAbstract
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
        $resolver->define('serial_number')
            ->required()
            ->allowedTypes('string')
            ->info('Device Serial Number');
    }
}