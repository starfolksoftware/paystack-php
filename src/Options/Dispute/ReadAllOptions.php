<?php

namespace StarfolkSoftware\Paystack\Options\Dispute;

use StarfolkSoftware\Paystack\Abstracts\OptionsAbstract;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReadAllOptions extends OptionsAbstract
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
        $resolver->define('from')
            ->allowedTypes('string')
            ->info('A timestamp from which to start listing disputes');

        $resolver->define('to')
            ->allowedTypes('string')
            ->info('A timestamp at which to stop listing disputes');

        $resolver->define('perPage')
            ->allowedTypes('int')
            ->info('Number of records to fetch per page');

        $resolver->define('page')
            ->allowedTypes('int')
            ->info('The section to retrieve');

        $resolver->define('transaction')
            ->allowedTypes('string')
            ->info('Transaction ID');

        $resolver->define('status')
            ->allowedTypes('string')
            ->allowedValues(['awaiting-merchant-feedback', 'awaiting-bank-feedback', 'pending', 'resolved'])
            ->info('Dispute Status. Acceptable values: { awaiting-merchant-feedback | awaiting-bank-feedback | pending | resolved }');
    }
}