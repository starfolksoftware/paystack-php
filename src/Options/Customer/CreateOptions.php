<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Options\Customer;

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
        $resolver->define('email')
            ->required()
            ->allowedTypes('string')
            ->info("Customer's email address");

        $resolver->define('first_name')
            ->required()
            ->allowedTypes('string')
            ->info("Customer's first name");

        $resolver->define('last_name')
            ->required()
            ->allowedTypes('string')
            ->info("Customer's last name");

        $resolver->define('phone')
            ->allowedTypes('string')
            ->info("Customer's phone number");

        $resolver->define('metadata')
            ->allowedTypes('array')
            ->info("A set of key/value pairs that you can attach to the customer. It can be used to store additional information in a structured format.");
    }
}
