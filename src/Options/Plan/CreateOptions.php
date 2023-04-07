<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Options\Plan;

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
            ->info('The name of the plan');

        $resolver->define('amount')
            ->required()
            ->allowedTypes('int')
            ->info('The amount of the plan');

        $resolver->define('interval')
            ->required()
            ->allowedTypes('string')
            ->allowedValues('daily', 'weekly', 'monthly', 'biannually', 'yearly')
            ->info('The interval of the plan');

        $resolver->define('description')
            ->allowedTypes('string')
            ->info('The plan description');

        $resolver->define('send_invoices')
            ->allowedTypes('bool')
            ->info("Set to false if you don't want invoices to be sent to your customers");
            
        $resolver->define('send_sms')
            ->allowedTypes('bool')
            ->info("Set to false if you don't want text messages to be sent to your customers");

        $resolver->define('currency')
            ->allowedTypes('string')
            ->info("Currency in which amount is set. Allowed values are NGN, GHS, ZAR or USD");

        $resolver->define('invoice_limit')
            ->allowedTypes('int')
            ->info("Number of invoices to raise during subscription to this plan.");
    }
}
