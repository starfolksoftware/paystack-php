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
        $resolver->define('resolution')
            ->required()
            ->allowedTypes('string')
            ->allowedValues(['merchant-accepted', 'declined'])
            ->info('Dispute resolution. Accepted values: merchant-accepted, declined');

        $resolver->define('message')
            ->required()
            ->allowedTypes('string')
            ->info('Reason for resolution');

        $resolver->define('refund_amount')
            ->allowedTypes('int')
            ->info('The amount to refund, in kobo if currency is NGN, pesewas, if currency is GHS, and cents, if currency is ZAR');

        $resolver->define('uploaded_filename')
            ->allowedTypes('string')
            ->info('Filename of attachment returned via response from upload url');

        $resolver->define('evidence')
            ->allowedTypes('int')
            ->info('Evidence Id for fraud claims');
    }
}