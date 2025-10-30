<?php

namespace StarfolkSoftware\Paystack\Options\VirtualTerminal;

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
            ->info('Name of the virtual terminal');

        $resolver->define('description')
            ->allowedTypes('string')
            ->info('Description of the virtual terminal');

        $resolver->define('currency')
            ->allowedTypes('string')
            ->info('Currency for the virtual terminal');

        $resolver->define('merchant_category_code')
            ->allowedTypes('string')
            ->info('Merchant category code for the virtual terminal');
    }
}