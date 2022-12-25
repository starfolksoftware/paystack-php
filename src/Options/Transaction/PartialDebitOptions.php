<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Options\Transaction;

use StarfolkSoftware\Paystack\Abstracts\OptionsAbstract;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class PartialDebitOptions extends OptionsAbstract
{
    /**
     * Set defaults, allowed types and values of the options.
     * 
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->define('amount')
            ->required()
            ->allowedTypes('string')
            ->info('Amount should be in kobo if currency is NGN, pesewas, if currency is GHS, and cents, if currency is ZAR');

        $resolver->define('email')
            ->required()
            ->allowedTypes('string')
            ->info("Customer's email address");

        $resolver->define('authorization_code')
            ->allowedTypes('string')
            ->info('Valid authorization code to charge.');

        $resolver->define('reference')
            ->allowedTypes('string')
            ->info("Unique transaction reference. Only -, ., = and alphanumeric characters allowed.");
            
        $resolver->define('currency')
            ->allowedTypes('string')
            ->info("Currency in which amount should be charged. Allowed values are: NGN, GHS, ZAR or USD");
            
        $resolver->define('at_least')
            ->allowedTypes('string')
            ->info("Minimum amount to charge");
    }
}
