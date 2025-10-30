<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Options\Miscellaneous;

use StarfolkSoftware\Paystack\Abstracts\OptionsAbstract;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListStatesOptions extends OptionsAbstract
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
            ->required()
            ->allowedTypes('string')
            ->info('The country code of the country whose states you want to retrieve. e.g. country=NG');
    }
}