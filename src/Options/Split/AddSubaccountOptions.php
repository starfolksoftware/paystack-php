<?php

namespace StarfolkSoftware\Paystack\Options\Split;

use StarfolkSoftware\Paystack\Abstracts\OptionsAbstract;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddSubaccountOptions extends OptionsAbstract
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

        $resolver->define('share')
            ->required()
            ->allowedTypes('int')
            ->info('This is the transaction share for the subaccount');
    }
}