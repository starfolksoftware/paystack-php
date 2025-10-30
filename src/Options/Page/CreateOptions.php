<?php

namespace StarfolkSoftware\Paystack\Options\Page;

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
            ->info('Name of page');

        $resolver->define('description')
            ->allowedTypes('string')
            ->info('A description for this page');

        $resolver->define('amount')
            ->allowedTypes('int')
            ->info('Amount should be in kobo if currency is NGN, pesewas, if currency is GHS, and cents, if currency is ZAR');

        $resolver->define('slug')
            ->allowedTypes('string')
            ->info('URL slug you would like to be associated with this page. Page will be accessible at https://paystack.com/pay/[slug]');

        $resolver->define('metadata')
            ->allowedTypes('array')
            ->info('Extra data to configure the payment page including subaccount, logo image, transaction charge');

        $resolver->define('redirect_url')
            ->allowedTypes('string')
            ->info('If you would like Paystack to redirect someplace upon successful payment, specify the URL here.');

        $resolver->define('custom_fields')
            ->allowedTypes('array')
            ->info('If you would like to accept custom fields, specify them here.');
    }
}