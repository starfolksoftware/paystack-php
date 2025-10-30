<?php

namespace StarfolkSoftware\Paystack\Options\TransferRecipient;

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
        $resolver->define('type')
            ->required()
            ->allowedTypes('string')
            ->allowedValues(['nuban', 'mobile_money', 'basa'])
            ->info('Recipient Type. It could be one of: nuban, mobile_money, basa');

        $resolver->define('name')
            ->required()
            ->allowedTypes('string')
            ->info('A name for the recipient');

        $resolver->define('account_number')
            ->required()
            ->allowedTypes('string')
            ->info('Required if type is nuban or basa');

        $resolver->define('bank_code')
            ->required()
            ->allowedTypes('string')
            ->info('Required if type is nuban or basa. You can get the list of Bank Codes by calling the List Banks endpoint');

        $resolver->define('description')
            ->allowedTypes('string')
            ->info('A description for this recipient');

        $resolver->define('currency')
            ->allowedTypes('string')
            ->info('Currency for the account receiving the transfer');

        $resolver->define('authorization_code')
            ->allowedTypes('string')
            ->info('An authorization code from a previous transaction');

        $resolver->define('metadata')
            ->allowedTypes('array')
            ->info('Store additional information about your recipient in a structured format, JSON');
    }
}