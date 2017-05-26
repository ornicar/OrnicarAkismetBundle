<?php

namespace Ornicar\AkismetBundle\Akismet;

/**
 * Always tells the data is not spam.
 * Use it when you want to skip spam detection,
 * like during tests or when loading fixtures.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class AkismetStub implements AkismetInterface
{
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
    public function isSpam(array $data): bool
    {
        return false;
    }

    function submitSpam(array $data)
    {
    }
}
