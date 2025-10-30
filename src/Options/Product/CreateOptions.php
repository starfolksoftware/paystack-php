<?php

namespace StarfolkSoftware\Paystack\Options\Product;

use StarfolkSoftware\Paystack\Abstracts\OptionsAbstract;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateOptions extends OptionsAbstract
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
        $resolver->define('name')
            ->required()
            ->allowedTypes('string')
            ->info('Name of product');

        $resolver->define('description')
            ->required()
            ->allowedTypes('string')
            ->info('A description for this product');

        $resolver->define('price')
            ->required()
            ->allowedTypes('int')
            ->info('Price should be in kobo if currency is NGN, pesewas, if currency is GHS, and cents, if currency is ZAR');

        $resolver->define('currency')
            ->required()
            ->allowedTypes('string')
            ->info('Currency in which price is set. Allowed values are: NGN, GHS, ZAR or USD');

        $resolver->define('unlimited')
            ->allowedTypes('bool')
            ->info('Set to true if the product has unlimited stock. Leave as false if the product has limited stock');

        $resolver->define('quantity')
            ->allowedTypes('int')
            ->info('Number of products in stock. Use if unlimited is false');
    }
}