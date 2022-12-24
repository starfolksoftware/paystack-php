<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Options\Subscription;

use StarfolkSoftware\Paystack\Abstracts\OptionsAbstract;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class CreateOptions extends OptionsAbstract
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
        $resolver->define('customer')
            ->required()
            ->allowedTypes('string')
            ->info("Customer's email address or customer code");

        $resolver->define('plan')
            ->required()
            ->allowedTypes('string')
            ->info('Plan code');

        $resolver->define('authorization')
            ->allowedTypes('string')
            ->info('The interval of the plan');

        $resolver->define('description')
            ->allowedTypes('string')
            ->info("If customer has multiple authorizations, you can set the desired authorization you wish to use for this subscription here. If this is not supplied, the customer's most recent authorization would be used");

        $resolver->define('start_date')
            ->allowedTypes('string')
            ->info("Set the date for the first debit. (ISO 8601 format) e.g. 2017-05-16T00:30:13+01:00");
    }
}
