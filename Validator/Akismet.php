<?php

namespace Ornicar\AkismetBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Akismet extends Constraint
{
    public $message = 'Value has been identified as spam.';
    public $author  = 'author';
    public $content = 'content';

    public function validatedBy()
    {
        return 'akismet';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
