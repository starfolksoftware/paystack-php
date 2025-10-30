<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Options\Miscellaneous;

use StarfolkSoftware\Paystack\Abstracts\OptionsAbstract;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListBanksOptions extends OptionsAbstract
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
        $resolver->define('country')
            ->allowedTypes('string')
            ->info('The country from which to obtain the list of supported banks. e.g country=ghana or country=nigeria');

        $resolver->define('use_cursor')
            ->allowedTypes('bool')
            ->info('Use cursor pagination instead of classic pagination');

        $resolver->define('perPage')
            ->allowedTypes('int')
            ->info('Specify how many records you want to retrieve per page. If not specify we use a default value of 50.');

        $resolver->define('next')
            ->allowedTypes('string')
            ->info('A cursor that indicates your place in the list. It can be used to fetch the next page of the list');

        $resolver->define('previous')
            ->allowedTypes('string')
            ->info('A cursor that indicates your place in the list. It can be used to fetch the previous page of the list');
    }
}