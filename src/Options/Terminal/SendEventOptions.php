<?php

namespace StarfolkSoftware\Paystack\Options\Terminal;

use StarfolkSoftware\Paystack\Abstracts\OptionsAbstract;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SendEventOptions extends OptionsAbstract
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
        $resolver->define('type')
            ->required()
            ->allowedTypes('string')
            ->allowedValues('invoice', 'transaction')
            ->info('The type of event to push. We currently support invoice and transaction');

        $resolver->define('action')
            ->required()
            ->allowedTypes('string')
            ->info('The action the Terminal needs to perform. For the invoice type, the action can either be process or view. For the transaction type, the action can either be process or print.');

        $resolver->define('data')
            ->required()
            ->allowedTypes('array')
            ->info('The paramters needed to perform the specified action. For the invoice type, you need to pass the invoice id and offline reference: {id: invoice_id, reference: offline_reference}. For the transaction type, you can pass the transaction id: {id: transaction_id}');
    }
}