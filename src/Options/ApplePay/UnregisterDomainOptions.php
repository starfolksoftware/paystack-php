<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Options\ApplePay;

use StarfolkSoftware\Paystack\Abstracts\OptionsAbstract;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UnregisterDomainOptions extends OptionsAbstract
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
        $resolver->define('domainName')
            ->required()
            ->allowedTypes('string')
            ->info('Domain name to be unregistered');
    }
}