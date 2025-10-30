<?php

namespace StarfolkSoftware\Paystack\Options\VirtualTerminal;

use StarfolkSoftware\Paystack\Abstracts\OptionsAbstract;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SplitCodeOptions extends OptionsAbstract
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
        $resolver->define('split_code')
            ->required()
            ->allowedTypes('string')
            ->info('Split code to add or remove from the virtual terminal');
    }
}