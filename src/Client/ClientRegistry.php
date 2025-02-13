<?php

/*
 * OAuth2 Client Bundle
 * Copyright (c) KnpUniversity <http://knpuniversity.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KnpU\OAuth2ClientBundle\Client;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class ClientRegistry
{
    private ContainerInterface $container;

    private array $serviceMap;

    /**
     * ClientRegistry constructor.
     */
    public function __construct(ContainerInterface $container, array $serviceMap)
    {
        $this->container = $container;
        $this->serviceMap = $serviceMap;
    }

    /**
     * Easy accessor for client objects.
     *
     * @param string $key
     *
     * @return OAuth2ClientInterface
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getClient(string $key): OAuth2ClientInterface
    {
        if (isset($this->serviceMap[$key])) {
            $client = $this->container->get($this->serviceMap[$key]);
            if (!$client instanceof OAuth2ClientInterface) {
                throw new \InvalidArgumentException(
                    sprintf(
                        'Somehow the "%s" client is not an instance of OAuth2ClientInterface.',
                        $key
                    )
                );
            }

            return $client;
        }

        throw new \InvalidArgumentException(
            sprintf(
                'There is no OAuth2 client called "%s". Available are: %s',
                $key,
                implode(', ', array_keys($this->serviceMap))
            )
        );
    }

    /**
     * Returns all enabled client keys.
     *
     * @return array
     */
    public function getEnabledClientKeys(): array
    {
        return array_keys($this->serviceMap);
    }
}
