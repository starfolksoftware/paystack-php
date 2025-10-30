<?php

namespace StarfolkSoftware\Paystack\Options\Subaccount;

use StarfolkSoftware\Paystack\Abstracts\OptionsAbstract;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateOptions extends OptionsAbstract
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
            ->allowedTypes('string')
            ->info('Name of business for subaccount');

        $resolver->define('settlement_bank')
            ->allowedTypes('string')
            ->info('Bank Code for the bank');

        $resolver->define('account_number')
            ->allowedTypes('string')
            ->info('Bank Account Number');

        $resolver->define('active')
            ->allowedTypes('bool')
            ->info('Activate or deactivate a subaccount');

        $resolver->define('percentage_charge')
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

        $resolver->define('settlement_schedule')
            ->allowedTypes('string')
            ->allowedValues(['auto', 'weekly', 'monthly', 'manual'])
            ->info('Any of auto, weekly, monthly, manual. Auto means payout is T+1 and manual means payout to the subaccount should only be made when requested');

        $resolver->define('metadata')
            ->allowedTypes('array')
            ->info('Stringified JSON object of custom data');
    }
}