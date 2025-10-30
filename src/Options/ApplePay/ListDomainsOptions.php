<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Options\ApplePay;

use StarfolkSoftware\Paystack\Abstracts\OptionsAbstract;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListDomainsOptions extends OptionsAbstract
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
        $resolver->setDefined(['use_cursor', 'next', 'previous']);
        $resolver->setAllowedTypes('use_cursor', ['bool', 'string']);
        $resolver->setAllowedTypes('next', 'string');
        $resolver->setAllowedTypes('previous', 'string');
    }

    /**
     * Get the options converted for HTTP transmission.
     * 
     * @return array
     */
    public function all(): array
    {
        $options = parent::all();
        
        // Convert boolean to string for HTTP query parameters
        if (isset($options['use_cursor'])) {
            $options['use_cursor'] = $options['use_cursor'] ? 'true' : 'false';
        }
        
        return $options;
    }
}