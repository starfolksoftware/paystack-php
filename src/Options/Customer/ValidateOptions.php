<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Options\Customer;

use StarfolkSoftware\Paystack\Abstracts\OptionsAbstract;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ValidateOptions extends OptionsAbstract
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
        $resolver->define('first_name')
            ->required()
            ->allowedTypes('string')
            ->info("Customer's first name");

        $resolver->define('last_name')
            ->required()
            ->allowedTypes('string')
            ->info("Customer's last name");

        $resolver->define('type')
            ->required()
            ->allowedTypes('string')
            ->info("Predefined types of identification. Only bank_account is supported at the moment");

        $resolver->define('value')
            ->required()
            ->allowedTypes('string')
            ->info("Customer's identification number");

        $resolver->define('country')
            ->required()
            ->allowedTypes('string')
            ->info("2 letter country code of identification issuer");

        $resolver->define('bvn')
            ->required()
            ->allowedTypes('string')
            ->info("Customer's Bank Verification Number");

        $resolver->define('bank_code')
            ->required()
            ->allowedTypes('string')
            ->info("You can get the list of Bank Codes by calling the List Banks endpoint. (required if type is bank_account)");

        $resolver->define('account_number')
            ->required()
            ->allowedTypes('string')
            ->info("Customer's bank account number. (required if type is bank_account)");

        $resolver->define('middle_name')
            ->allowedTypes('string')
            ->info("Customer's middle name");
    }
}
