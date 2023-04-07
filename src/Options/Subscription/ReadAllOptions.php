<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Options\Subscription;

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
            ->info("Specify how many records you want to retrieve per page. If not specify we use a default value of 50.");

        $resolver->define('page')
            ->allowedTypes('int')
            ->info("Specify exactly what page you want to retrieve. If not specify we use a default value of 1.");

        $resolver->define('customer')
            ->allowedTypes('string')
            ->info("Filter by Customer ID");

        $resolver->define('plan')
            ->allowedTypes('string')
            ->info("Filter by Plan ID");
    }
}
