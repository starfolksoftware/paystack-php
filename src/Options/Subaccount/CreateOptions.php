<?php

namespace StarfolkSoftware\Paystack\Options\Subaccount;

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
        $resolver->define('business_name')
            ->required()
            ->allowedTypes('string')
            ->info('Name of business for subaccount');

        $resolver->define('settlement_bank')
            ->required()
            ->allowedTypes('string')
            ->info('Bank Code for the bank. You can get the list of Bank Codes by calling the List Banks endpoint');

        $resolver->define('account_number')
            ->required()
            ->allowedTypes('string')
            ->info('Bank Account Number');

        $resolver->define('percentage_charge')
            ->required()
            ->allowedTypes('float', 'int')
            ->info('The default percentage charged when receiving on behalf of this subaccount');

        $resolver->define('description')
            ->allowedTypes('string')
            ->info('A description for this subaccount');

        $resolver->define('primary_contact_email')
            ->allowedTypes('string')
            ->info('A contact email for the subaccount');

        $resolver->define('primary_contact_name')
            ->allowedTypes('string')
            ->info('A name for the contact person for this subaccount');

        $resolver->define('primary_contact_phone')
            ->allowedTypes('string')
            ->info('A phone number to call for this subaccount');

        $resolver->define('metadata')
            ->allowedTypes('array')
            ->info('Stringified JSON object of custom data');
    }
}