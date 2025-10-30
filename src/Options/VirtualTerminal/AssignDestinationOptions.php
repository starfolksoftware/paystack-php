<?php

namespace StarfolkSoftware\Paystack\Options\VirtualTerminal;

use StarfolkSoftware\Paystack\Abstracts\OptionsAbstract;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AssignDestinationOptions extends OptionsAbstract
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
        $resolver->define('destination')
            ->required()
            ->allowedTypes('string')
            ->info('WhatsApp number to assign as destination');
    }
}