<?php

namespace StarfolkSoftware\Paystack\Options\DedicatedVirtualAccount;

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
        $resolver->define('customer')
            ->required()
            ->allowedTypes('string')
            ->info('Customer ID or code');

        $resolver->define('preferred_bank')
            ->allowedTypes('string')
            ->info('The bank slug for preferred bank. To get a list of available banks, use the List Providers endpoint');

        $resolver->define('subaccount')
            ->allowedTypes('string')
            ->info('Subaccount code of the account you want to split the transaction with');

        $resolver->define('split_code')
            ->allowedTypes('string')
            ->info('Split code consisting of the lists of accounts you want to split the transaction with');

        $resolver->define('first_name')
            ->allowedTypes('string')
            ->info('Customer\'s first name');

        $resolver->define('last_name')
            ->allowedTypes('string')
            ->info('Customer\'s last name');

        $resolver->define('phone')
            ->allowedTypes('string')
            ->info('Customer\'s phone number');
    }
}