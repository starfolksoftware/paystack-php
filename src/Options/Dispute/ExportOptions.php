<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Options\Dispute;

use StarfolkSoftware\Paystack\Abstracts\OptionsAbstract;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExportOptions extends OptionsAbstract
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
            ->info('A timestamp from which to start listing dispute e.g. 2016-09-24T00:00:05.000Z, 2016-09-21');

        $resolver->define('to')
            ->allowedTypes('string')
            ->info('A timestamp at which to stop listing dispute e.g. 2016-09-24T00:00:05.000Z, 2016-09-21');

        $resolver->define('perPage')
            ->allowedTypes('int')
            ->info('Specify how many records you want to retrieve per page. If not specify we use a default value of 50.');

        $resolver->define('page')
            ->allowedTypes('int')
            ->info('Specify exactly what dispute you want to page. If not specify we use a default value of 1.');

        $resolver->define('transaction')
            ->allowedTypes('string')
            ->info('Transaction ID');

        $resolver->define('status')
            ->allowedTypes('string')
            ->allowedValues(['awaiting-merchant-feedback', 'awaiting-bank-feedback', 'pending', 'resolved'])
            ->info('Dispute Status. Accepted values: awaiting-merchant-feedback, awaiting-bank-feedback, pending, resolved');
    }
}