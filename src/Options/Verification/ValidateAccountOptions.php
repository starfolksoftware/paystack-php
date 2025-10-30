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
        $resolver->setRequired(['bank_code', 'country_code', 'account_number', 'account_name', 'account_type', 'document_type']);
        
        $resolver->setAllowedTypes('bank_code', 'string');
        $resolver->setAllowedTypes('country_code', 'string');
        $resolver->setAllowedTypes('account_number', 'string');
        $resolver->setAllowedTypes('account_name', 'string');
        $resolver->setAllowedTypes('account_type', 'string');
        $resolver->setAllowedValues('account_type', ['personal', 'business']);
        $resolver->setAllowedTypes('document_type', 'string');
        $resolver->setAllowedValues('document_type', ['identityNumber', 'passportNumber', 'businessRegistrationNumber']);

        $resolver->setDefined(['document_number']);
        $resolver->setAllowedTypes('document_number', 'string');
    }
}