<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack\Options\Dispute;

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
        $resolver->define('refund_amount')
            ->required()
            ->allowedTypes('int')
            ->info('The amount to refund, in the subunit of the supported currency');

        $resolver->define('uploaded_filename')
            ->allowedTypes('string')
            ->info('filename of attachment returned via response from upload url(GET /dispute/:id/upload_url)');
    }
}