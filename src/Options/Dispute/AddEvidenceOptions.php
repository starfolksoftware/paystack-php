<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Options\Dispute;

use StarfolkSoftware\Paystack\Abstracts\OptionsAbstract;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddEvidenceOptions extends OptionsAbstract
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
        $resolver->define('customer_email')
            ->required()
            ->allowedTypes('string')
            ->info('Customer email');

        $resolver->define('customer_name')
            ->required()
            ->allowedTypes('string')
            ->info('Customer name');

        $resolver->define('customer_phone')
            ->required()
            ->allowedTypes('string')
            ->info('Customer phone');

        $resolver->define('service_details')
            ->required()
            ->allowedTypes('string')
            ->info('Details of service involved');

        $resolver->define('delivery_address')
            ->allowedTypes('string')
            ->info('Delivery address');

        $resolver->define('delivery_date')
            ->allowedTypes('string')
            ->info('ISO 8601 representation of delivery date');
    }
}