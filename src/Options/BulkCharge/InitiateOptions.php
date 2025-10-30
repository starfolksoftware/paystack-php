<?php

namespace StarfolkSoftware\Paystack\Options\BulkCharge;

use StarfolkSoftware\Paystack\Abstracts\OptionsAbstract;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InitiateOptions extends OptionsAbstract
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
        $resolver->define('charges')
            ->required()
            ->allowedTypes('array')
            ->info('A list of charge objects. Each object should contain: authorization, amount, and reference');
    }
}