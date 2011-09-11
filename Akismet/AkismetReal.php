<?php

namespace Ornicar\AkismetBundle\Akismet;

use Symfony\Component\HttpFoundation\Request;
use Ornicar\AkismetBundle\Adapter\AkismetAdapterInterface;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

/**
 * Detects spam by querying the Akismet service.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class AkismetReal implements AkismetInterface
{
    /**
     * @var Request
     */
    protected $request;

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
     * Constructor.
     *
     * @param AkismetAdapterInterface $adapter
     * @param Request $request
     * @param boolean $throwExceptions if false, exceptions are just ignored
     */
    public function __construct(AkismetAdapterInterface $adapter, Request $request, $throwExceptions, LoggerInterface $logger = null)
    {
        $this->adapter = $adapter;
        $this->request = $request;
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
        return array(
            'permalink'  => $this->request->getUri(),
            'user_ip'    => $this->request->getClientIp(),
            'user_agent' => $this->request->server->get('HTTP_USER_AGENT'),
            'referrer'   => $this->request->server->get('HTTP_REFERER'),
        );
    }
}
