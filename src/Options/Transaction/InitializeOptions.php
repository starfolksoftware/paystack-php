<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Options\Transaction;

use StarfolkSoftware\Paystack\Abstracts\OptionsAbstract;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class InitializeOptions extends OptionsAbstract
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
        $resolver->define('amount')
            ->required()
            ->allowedTypes('string')
            ->info('Amount should be in kobo if currency is NGN, pesewas, if currency is GHS, and cents, if currency is ZAR');

        $resolver->define('email')
            ->required()
            ->allowedTypes('string')
            ->info("Customer's email address");

        $resolver->define('currency')
            ->allowedTypes('string')
            ->info('The transaction currency (NGN, GHS, ZAR or USD). Defaults to your integration currency.');

        $resolver->define('reference')
            ->allowedTypes('string')
            ->info("Unique transaction reference. Only -, ., = and alphanumeric characters allowed.");
            
        $resolver->define('callback_url')
            ->allowedTypes('string')
            ->info("Fully qualified url, e.g. https://example.com/ . Use this to override the callback url provided on the dashboard for this transaction");

        $resolver->define('plan')
            ->allowedTypes('string')
            ->info("If transaction is to create a subscription to a predefined plan, provide plan code here. This would invalidate the value provided in amount");

        $resolver->define('invoice_limit')
            ->allowedTypes('int')
            ->info("Number of times to charge customer during subscription to plan");

        $resolver->define('metadata')
            ->allowedTypes('array')
            ->info("Stringified JSON object of custom data. Kindly check the Metadata page for more information.");

        $resolver->define('channels')
            ->allowedTypes('array')
            ->info('An array of payment channels to control what channels you want to make available to the user to make a payment with. Available channels include: ["card", "bank", "ussd", "qr", "mobile_money", "bank_transfer", "eft"]');

        $resolver->define('split_code')
            ->allowedTypes('string')
            ->info("The split code of the transaction split. e.g. SPL_98WF13Eb3w");

        $resolver->define('subaccount')
            ->allowedTypes('string')
            ->info("The code for the subaccount that owns the payment. e.g. ACCT_8f4s1eq7ml6rlzj");

        $resolver->define('transaction_charge')
            ->allowedTypes('int')
            ->info("An amount used to override the split configuration for a single split payment. If set, the amount specified goes to the main account regardless of the split configuration.");

        $resolver->define('bearer')
            ->allowedTypes('string')
            ->info("Who bears Paystack charges? account or subaccount (defaults to account).");
    }
}
