<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Options\Verification;

use StarfolkSoftware\Paystack\Abstracts\OptionsAbstract;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResolveAccountOptions extends OptionsAbstract
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
            ->info('Account Number');

        $resolver->define('bank_code')
            ->required()
            ->allowedTypes('string')
            ->info('Bank Code. You can get the list of Bank Codes by calling the List Banks endpoint.');
    }
}