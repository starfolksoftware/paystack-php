<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Options\Transaction;

use StarfolkSoftware\Paystack\Abstracts\OptionsAbstract;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ChargeOptions extends OptionsAbstract
{
    /**
     * Set defaults, allowed types and values of the options.
     * 
     * @param OptionsResolver $resolver
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

        $resolver->define('authorization_code')
            ->allowedTypes('string')
            ->info('Valid authorization code to charge.');

        $resolver->define('reference')
            ->allowedTypes('string')
            ->info("Unique transaction reference. Only -, ., = and alphanumeric characters allowed.");
            
        $resolver->define('currency')
            ->allowedTypes('string')
            ->info("Currency in which amount should be charged. Allowed values are: NGN, GHS, ZAR or USD");

        $resolver->define('metadata')
            ->allowedTypes('array')
            ->info('Stringified JSON object. Add a custom_fields attribute which has an array of objects if you would like the fields to be added to your transaction when displayed on the dashboard. Sample: {"custom_fields":[{"display_name":"Cart ID","variable_name": "cart_id","value": "8393"}]}');

        $resolver->define('channels')
            ->allowedTypes('array')
            ->info('An array of payment channels to control what channels you want to make available to the user to make a payment with. Available channels include: ["card", "bank", "ussd", "qr", "mobile_money", "bank_transfer", "eft"]');

        $resolver->define('subaccount')
            ->allowedTypes('string')
            ->info("The code for the subaccount that owns the payment. e.g. ACCT_8f4s1eq7ml6rlzj");

        $resolver->define('transaction_charge')
            ->allowedTypes('int')
            ->info("A flat fee to charge the subaccount for this transaction (in kobo if currency is NGN, pesewas, if currency is GHS, and cents, if currency is ZAR). This overrides the split percentage set when the subaccount was created. Ideally, you will need to use this if you are splitting in flat rates (since subaccount creation only allows for percentage split). e.g. 7000 for a 70 naira");

        $resolver->define('bearer')
            ->allowedTypes('string')
            ->info("Who bears Paystack charges? account or subaccount (defaults to account).");

        $resolver->define('queue')
            ->allowedTypes('bool')
            ->info("If you are making a scheduled charge call, it is a good idea to queue them so the processing system does not get overloaded causing transaction processing errors. Send queue:true to take advantage of our queued charging.");
    }
}
