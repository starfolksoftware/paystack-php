<?php

namespace StarfolkSoftware\Paystack\Options\Transfer;

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
        $resolver->define('source')
            ->required()
            ->allowedTypes('string')
            ->allowedValues(['balance'])
            ->info('Where should we transfer from? Only balance for now');

        $resolver->define('amount')
            ->required()
            ->allowedTypes('int')
            ->info('Amount to transfer in kobo if currency is NGN, pesewas, if currency is GHS, and cents, if currency is ZAR');

        $resolver->define('recipient')
            ->required()
            ->allowedTypes('string')
            ->info('Code for transfer recipient');

        $resolver->define('reason')
            ->allowedTypes('string')
            ->info('The reason for the transfer');

        $resolver->define('currency')
            ->allowedTypes('string')
            ->info('Currency in which to make the transfer. Defaults to NGN');

        $resolver->define('reference')
            ->allowedTypes('string')
            ->info('If specified, the field should be a unique identifier (in lowercase) for the object. Only -,_ and alphanumeric characters allowed.');
    }
}