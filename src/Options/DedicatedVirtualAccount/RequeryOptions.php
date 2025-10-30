<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Options\DedicatedVirtualAccount;

use StarfolkSoftware\Paystack\Abstracts\OptionsAbstract;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RequeryOptions extends OptionsAbstract
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
            ->info('Virtual account number to requery');

        $resolver->define('provider_slug')
            ->required()
            ->allowedTypes('string')
            ->info('The bank provider slug for the dedicated virtual account');

        $resolver->define('date')
            ->allowedTypes('string')
            ->info('The day the DVA transaction occurred');
    }
}