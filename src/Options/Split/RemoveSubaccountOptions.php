<?php

namespace StarfolkSoftware\Paystack\Options\Split;

use StarfolkSoftware\Paystack\Abstracts\OptionsAbstract;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RemoveSubaccountOptions extends OptionsAbstract
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
        $resolver->define('subaccount')
            ->required()
            ->allowedTypes('string')
            ->info('This is the sub account code');
    }
}