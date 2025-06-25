<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Options\PaymentRequest;

use StarfolkSoftware\Paystack\Abstracts\OptionsAbstract;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateOptions extends OptionsAbstract
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
            ->allowedTypes('string')
            ->info('Customer id or code');

        $resolver->define('amount')
            ->allowedTypes('int')
            ->info('Payment request amount. Only useful if line items and tax values are ignored.');

        $resolver->define('due_date')
            ->allowedTypes('string')
            ->info('ISO 8601 representation of request due date');

        $resolver->define('description')
            ->allowedTypes('string')
            ->info('A short description of the payment request');

        $resolver->define('line_items')
            ->allowedTypes('array')
            ->info('Array of line items in the format [{"name":"item 1", "amount":2000}]');

        $resolver->define('tax')
            ->allowedTypes('array')
            ->info('Array of taxes to be charged in the format [{"name":"VAT", "amount":2000}]');

        $resolver->define('currency')
            ->allowedTypes('string')
            ->info('Specify the currency of the payment request. Defaults to NGN.');

        $resolver->define('send_notification')
            ->allowedTypes('bool')
            ->info('Indicates whether Paystack sends an email notification to customer. Defaults to true');

        $resolver->define('draft')
            ->allowedTypes('bool')
            ->info('Indicate if request should be saved as draft. Defaults to false and overrides send_notification');

        $resolver->define('invoice_number')
            ->allowedTypes('int')
            ->info('Numeric value of the payment request. Payment Requests will start from 1 and auto increment from there.');

        $resolver->define('split_code')
            ->allowedTypes('string')
            ->info('The split code of the transaction split. e.g. SPL_98WF13Eb3w');
    }
}
