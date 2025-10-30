<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Options\Charge;

use StarfolkSoftware\Paystack\Abstracts\OptionsAbstract;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubmitAddressOptions extends OptionsAbstract
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
        $resolver->define('address')
            ->required()
            ->allowedTypes('string')
            ->info('Address submitted by user');

        $resolver->define('reference')
            ->required()
            ->allowedTypes('string')
            ->info('Reference for ongoing transaction');

        $resolver->define('city')
            ->required()
            ->allowedTypes('string')
            ->info('City submitted by user');

        $resolver->define('state')
            ->required()
            ->allowedTypes('string')
            ->info('State submitted by user');

        $resolver->define('zipcode')
            ->required()
            ->allowedTypes('string')
            ->info('Zipcode submitted by user');
    }
}