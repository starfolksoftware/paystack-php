<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Options\Charge;

use StarfolkSoftware\Paystack\Abstracts\OptionsAbstract;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubmitPhoneOptions extends OptionsAbstract
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
        $resolver->define('phone')
            ->required()
            ->allowedTypes('string')
            ->info('Phone number submitted by user');

        $resolver->define('reference')
            ->required()
            ->allowedTypes('string')
            ->info('Reference for ongoing transaction');
    }
}