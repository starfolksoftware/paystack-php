<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Options\DedicatedVirtualAccount;

use StarfolkSoftware\Paystack\Abstracts\OptionsAbstract;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AssignOptions extends OptionsAbstract
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
        $resolver->define('email')
            ->required()
            ->allowedTypes('string')
            ->info('Customer email address');

        $resolver->define('first_name')
            ->required()
            ->allowedTypes('string')
            ->info('Customer\'s first name');

        $resolver->define('last_name')
            ->required()
            ->allowedTypes('string')
            ->info('Customer\'s last name');

        $resolver->define('phone')
            ->required()
            ->allowedTypes('string')
            ->info('Customer\'s phone number');

        $resolver->define('preferred_bank')
            ->required()
            ->allowedTypes('string')
            ->info('The bank slug for preferred bank. To get a list of available banks, use the List Providers endpoint');

        $resolver->define('country')
            ->required()
            ->allowedTypes('string')
            ->info('Currently accepts NG and GH only');

        $resolver->define('account_number')
            ->allowedTypes('string')
            ->info('Customer\'s account number');

        $resolver->define('bvn')
            ->allowedTypes('string')
            ->info('Customer\'s Bank Verification Number (Nigeria only)');

        $resolver->define('bank_code')
            ->allowedTypes('string')
            ->info('Customer\'s bank code');

        $resolver->define('subaccount')
            ->allowedTypes('string')
            ->info('Subaccount code of the account you want to split the transaction with');

        $resolver->define('split_code')
            ->allowedTypes('string')
            ->info('Split code consisting of the lists of accounts you want to split the transaction with');
    }
}