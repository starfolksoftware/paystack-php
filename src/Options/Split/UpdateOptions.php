<?php

namespace StarfolkSoftware\Paystack\Options\Split;

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
            ->info('Name of the transaction split');

        $resolver->define('active')
            ->allowedTypes('bool')
            ->info('True or False');

        $resolver->define('bearer_type')
            ->allowedTypes('string')
            ->allowedValues(['subaccount', 'account', 'all-proportional', 'all'])
            ->info('Any of the following values: subaccount | account | all-proportional | all');

        $resolver->define('bearer_subaccount')
            ->allowedTypes('string')
            ->info('Subaccount code of a subaccount in the split group. This should be specified only if the bearer_type is subaccount');
    }
}