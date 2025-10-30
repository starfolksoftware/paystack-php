<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Options\DedicatedVirtualAccount;

use StarfolkSoftware\Paystack\Abstracts\OptionsAbstract;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RemoveSplitOptions extends OptionsAbstract
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
        $resolver->define('account_number')
            ->required()
            ->allowedTypes('string')
            ->info('Dedicated virtual account number');

        $resolver->define('subaccount')
            ->allowedTypes('string')
            ->info('Subaccount code of the account you want to remove from split the transaction with');

        $resolver->define('split_code')
            ->allowedTypes('string')
            ->info('Split code consisting of the lists of accounts you want to remove from split the transaction with');
    }
}