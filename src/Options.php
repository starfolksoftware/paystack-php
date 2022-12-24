<?php declare(strict_types=1);

namespace StarfolkSoftware\Paystack;

use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class Options
{
    private array $options;

    public function __construct(array $options = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $this->options = $resolver->resolve($options);
    }

    private function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['secretKey']);
        
        $resolver->setDefaults(
            [
                'clientBuilder' => new ClientBuilder(),
                'uriFactory' => Psr17FactoryDiscovery::findUriFactory(),
                'apiVersion' => 'v1',
                'uri' => 'https://api.paystack.co',
            ]
        );

        $resolver->setAllowedTypes('uri', 'string');
        $resolver->setAllowedTypes('apiVersion', 'string');
        $resolver->setAllowedTypes('secretKey', 'string');
        $resolver->setAllowedTypes('clientBuilder', ClientBuilder::class);
        $resolver->setAllowedTypes('uriFactory', UriFactoryInterface::class);
    }

    public function getClientBuilder(): ClientBuilder
    {
        return $this->options['clientBuilder'];
    }

    public function getUriFactory(): UriFactoryInterface
    {
        return $this->options['uriFactory'];
    }

    public function getUri(): UriInterface
    {
        return $this->getUriFactory()->createUri($this->options['uri']);
    }

    public function getApiVersion(): string
    {
        return $this->options['apiVersion'];
    }

    public function getSecretKey(): string
    {
        return $this->options['secretKey'];
    }
}
