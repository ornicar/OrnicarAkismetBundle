<?php

namespace Ornicar\AkismetBundle\Akismet;

use Symfony\Component\HttpFoundation\RequestStack;
use Ornicar\AkismetBundle\Adapter\AkismetAdapterInterface;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Exception\LogicException;

/**
 * Detects spam by querying the Akismet service.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class AkismetReal implements AkismetInterface
{
    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var AkismetAdapterInterface
     */
    protected $adapter;

    /**
     * Whether or not to throw the akismet exceptions
     *
     * @var boolean
     */
    protected $throwExceptions;

    /**
     * Optional logger instance
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param AkismetAdapterInterface $adapter
     * @param RequestStack $requestStack
     * @param boolean $throwExceptions if false, exceptions are just ignored
     * @param LoggerInterface|null $logger
     */
    public function __construct(AkismetAdapterInterface $adapter, RequestStack $requestStack, $throwExceptions, LoggerInterface $logger = null)
    {
        $this->adapter = $adapter;
        $this->requestStack = $requestStack;
        $this->throwExceptions = $throwExceptions;
        $this->logger = $logger;
    }

    /**
     * Returns true if Akismet believes the data is a spam
     *
     * @param array data only the model data. The request data is added automatically.
     *        Exemple:
     *        array(
     *            'comment_author' => 'Jack',
     *            'comment_content' => 'The moon core is made of cheese'
     *        )
     *
     * @return bool true if it is spam
     */
    public function isSpam(array $data)
    {
        $fullData = array_merge($this->getRequestData(), $data);

        if ($this->throwExceptions) {
            return $this->adapter->isSpam($fullData);
        }

        try {
            return $this->adapter->isSpam($fullData);
        } catch (\Exception $e) {
            if ($this->logger) {
                $this->logger->warn(sprintf('%s: %s(%s)', get_class($this), get_class($e), $e->getMessage()));
            }

            return false;
        }
    }

    /**
     * Compiles a list of information from the request
     *
     * @return array
     */
    protected function getRequestData()
    {
        $request = $this->requestStack->getCurrentRequest();
        if (null === $request) {
            throw new LogicException('No current request found.');
        }

        return array(
            'permalink'  => $request->getUri(),
            'user_ip'    => $request->getClientIp(),
            'user_agent' => $request->server->get('HTTP_USER_AGENT'),
            'referrer'   => $request->server->get('HTTP_REFERER'),
        );
    }
}
