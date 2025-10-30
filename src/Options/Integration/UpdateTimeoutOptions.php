<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Options\Integration;

use StarfolkSoftware\Paystack\Abstracts\OptionsAbstract;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateTimeoutOptions extends OptionsAbstract
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
        $resolver->define('timeout')
            ->required()
            ->allowedTypes('int')
            ->info('Time before stopping session in seconds. Set to 0 to cancel session timeouts');
    }
}