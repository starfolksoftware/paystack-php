<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Options\BulkCharge;

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
            ->info("Specify how many records you want to retrieve per page. If not specified, we use a default value of 50.");

        $resolver->define('page')
            ->allowedTypes('int')
            ->info("Specify exactly what transfer you want to page. If not specified, we use a default value of 1.");

        $resolver->define('from')
            ->allowedTypes('datetime')
            ->info("A timestamp from which to start listing batches e.g. 2016-09-24T00:00:05.000Z, 2016-09-21");

        $resolver->define('to')
            ->allowedTypes('datetime')
            ->info("A timestamp at which to stop listing batches e.g. 2016-09-24T00:00:05.000Z, 2016-09-21");
    }
}