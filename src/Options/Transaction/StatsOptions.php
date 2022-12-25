<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Options\Transaction;

use StarfolkSoftware\Paystack\Abstracts\OptionsAbstract;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class StatsOptions extends OptionsAbstract
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
            ->info("Specify how many records you want to retrieve per page. If not specify we use a default value of 50.");

        $resolver->define('page')
            ->allowedTypes('int')
            ->info("Specify exactly what page you want to retrieve. If not specify we use a default value of 1.");
            
        $resolver->define('from')
            ->allowedTypes('datetime')
            ->info("A timestamp from which to start listing transaction e.g. 2016-09-24T00:00:05.000Z, 2016-09-21");
            
        $resolver->define('to')
            ->allowedTypes('datetime')
            ->info("A timestamp at which to stop listing transaction e.g. 2016-09-24T00:00:05.000Z, 2016-09-21");
    }
}
