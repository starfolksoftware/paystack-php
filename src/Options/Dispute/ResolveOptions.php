<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Options\Dispute;

use StarfolkSoftware\Paystack\Abstracts\OptionsAbstract;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResolveOptions extends OptionsAbstract
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
        $resolver->setRequired(['resolution', 'message']);
        
        $resolver->setAllowedTypes('resolution', 'string');
        $resolver->setAllowedValues('resolution', ['merchant-accepted', 'declined']);
        
        $resolver->setAllowedTypes('message', 'string');

        $resolver->setDefined(['refund_amount', 'uploaded_filename', 'evidence']);
        $resolver->setAllowedTypes('refund_amount', 'int');
        $resolver->setAllowedTypes('uploaded_filename', 'string');
        $resolver->setAllowedTypes('evidence', 'int');
    }
}