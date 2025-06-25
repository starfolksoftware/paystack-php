<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Options\PaymentRequest;

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
        $resolver->define('perPage')
            ->allowedTypes('int')
            ->info('Specify how many records you want to retrieve per page. If not specified we use a default value of 50.');

        $resolver->define('page')
            ->allowedTypes('int')
            ->info('Specify exactly what page you want to retrieve. If not specified we use a default value of 1.');

        $resolver->define('customer')
            ->allowedTypes('string')
            ->info('Filter by customer ID');

        $resolver->define('status')
            ->allowedTypes('string')
            ->info('Filter by payment request status');

        $resolver->define('currency')
            ->allowedTypes('string')
            ->info('Filter by currency');

        $resolver->define('include_archive')
            ->allowedTypes('string')
            ->info('Show archived payment requests');

        $resolver->define('from')
            ->allowedTypes('string')
            ->info('A timestamp from which to start listing payment requests e.g. 2016-09-24T00:00:05.000Z, 2016-09-21');

        $resolver->define('to')
            ->allowedTypes('string')
            ->info('A timestamp at which to stop listing payment requests e.g. 2016-09-24T00:00:05.000Z, 2016-09-21');
    }
}
