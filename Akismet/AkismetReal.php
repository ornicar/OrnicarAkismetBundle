<?php

namespace Ornicar\AkismetBundle\Akismet;

use Symfony\Component\HttpFoundation\Request;
use Zend\Service\Akismet\Akismet as ZendAkismet;
use Zend\Service\Akismet\Exception as AkismetException;

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
     * @var Akismet
     */
    protected $akismet;

    /**
     * Whether or not to throw the akismet exceptions
     *
     * @var boolean
     */
    protected $throwExceptions;

    /**
     * Constructor.
     *
     * @param Akismet $akismet
     * @param Request $request
     * @param boolean $throwExceptions if false, exceptions are just ignored
     */
    public function __construct(ZendAkismet $akismet, Request $request, $throwExceptions)
    {
        $this->akismet = $akismet;
        $this->request = $request;
        $this->throwExceptions = $throwExceptions;
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
            return $this->akismet->isSpam($fullData);
        }

        try {
            return $this->akismet->isSpam($fullData);
        } catch (AkismetException $e) {
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
