<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Options\Verification;

use StarfolkSoftware\Paystack\Abstracts\OptionsAbstract;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ValidateAccountOptions extends OptionsAbstract
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
        $resolver->define('bank_code')
            ->required()
            ->allowedTypes('string')
            ->info('Bank Code. You can get the list of Bank Codes by calling the List Banks endpoint.');

        $resolver->define('country_code')
            ->required()
            ->allowedTypes('string')
            ->info('The country code for the bank e.g., GH for Ghana, NG for Nigeria, ZA for South Africa, etc');

        $resolver->define('account_number')
            ->required()
            ->allowedTypes('string')
            ->info('Account Number');

        $resolver->define('account_name')
            ->required()
            ->allowedTypes('string')
            ->info('Account Name');

        $resolver->define('account_type')
            ->required()
            ->allowedTypes('string')
            ->allowedValues(['personal', 'business'])
            ->info('Account Type. personal for personal accounts, business for business accounts');

        $resolver->define('document_type')
            ->required()
            ->allowedTypes('string')
            ->allowedValues(['identityNumber', 'passportNumber', 'businessRegistrationNumber'])
            ->info('Document Type. identityNumber for identity number, passportNumber for passport number, businessRegistrationNumber for business registration number');

        $resolver->define('document_number')
            ->allowedTypes('string')
            ->info('Document Number');
    }
}