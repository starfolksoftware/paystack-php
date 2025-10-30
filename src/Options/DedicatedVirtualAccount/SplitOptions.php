<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Options\DedicatedVirtualAccount;

use StarfolkSoftware\Paystack\Abstracts\OptionsAbstract;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SplitOptions extends OptionsAbstract
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

        $resolver->define('subaccount')
            ->allowedTypes('string')
            ->info('Subaccount code of the account you want to split the transaction with');

        $resolver->define('split_code')
            ->allowedTypes('string')
            ->info('Split code consisting of the lists of accounts you want to split the transaction with');

        $resolver->define('preferred_bank')
            ->allowedTypes('string')
            ->info('The bank slug for preferred bank. To get a list of available banks, use the List Providers endpoint');
    }
}