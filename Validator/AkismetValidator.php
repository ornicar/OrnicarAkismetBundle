<?php

namespace Ornicar\AkismetBundle\Validator;

use Ornicar\AkismetBundle\Akismet\AkismetInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AkismetValidator extends ConstraintValidator
{
    private $akismet;

    public function __construct(AkismetInterface $akismet)
    {
        $this->akismet = $akismet;
    }

    public function validate($value, Constraint $constraint)
    {
        $accessor = PropertyAccess::getPropertyAccessor();
        $author  = $accessor->getValue($value, $constraint->author);
        $content = $accessor->getValue($value, $constraint->content);
        
        $isSpam = $this->akismet->isSpam(array(
            'comment_author'  => $author,
            'comment_content' => $content
        ));

        if ($isSpam) {
            $this->context->addViolationAt($constraint->content, $constraint->message);
        }
    }
}
