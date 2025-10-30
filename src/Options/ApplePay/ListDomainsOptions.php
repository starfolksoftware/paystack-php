<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Options\ApplePay;

use StarfolkSoftware\Paystack\Abstracts\OptionsAbstract;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListDomainsOptions extends OptionsAbstract
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
        $resolver->define('use_cursor')
            ->allowedTypes('bool')
            ->info('Use cursor pagination instead of classic pagination');

        $resolver->define('next')
            ->allowedTypes('string')
            ->info('A cursor that indicates your place in the list. It can be used to fetch the next page of the list');

        $resolver->define('previous')
            ->allowedTypes('string')
            ->info('A cursor that indicates your place in the list. It can be used to fetch the previous page of the list');
    }
}