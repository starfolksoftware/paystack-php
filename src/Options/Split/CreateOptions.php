<?php

namespace StarfolkSoftware\Paystack\Options\Split;

use StarfolkSoftware\Paystack\Abstracts\OptionsAbstract;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateOptions extends OptionsAbstract
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
            ->required()
            ->allowedTypes('string')
            ->info('Name of the transaction split');

        $resolver->define('type')
            ->required()
            ->allowedTypes('string')
            ->allowedValues('percentage', 'flat')
            ->info('The type of transaction split you want to create. You can use one of the following: percentage | flat');

        $resolver->define('currency')
            ->required()
            ->allowedTypes('string')
            ->info('Any of the supported currency');

        $resolver->define('subaccounts')
            ->required()
            ->allowedTypes('array')
            ->info('A list of object containing subaccount code and number of shares: [{"subaccount": "ACT_xxxxxxxxxx", "share": xxx},{...}]');

        $resolver->define('bearer_type')
            ->allowedTypes('string')
            ->allowedValues('subaccount', 'account', 'all-proportional', 'all')
            ->info('Any of subaccount | account | all-proportional | all');

        $resolver->define('bearer_subaccount')
            ->allowedTypes('string')
            ->info('Subaccount code');
    }
}