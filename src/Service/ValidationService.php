<?php

namespace App\Service;

use Symfony\Component\Validator\Constraints\File as FileValidator;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\HttpFoundation\File\File;

class ValidationService {

    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validateFile(File $file, ?array $constraints = null): array
    {
        // todo: get constraints from config

        $violations = $this->validator->validate($file, $constraints);
                
        if ($violations->count() > 0) {
            return [false, $violations->count(), $violations];
        }
        
        return [true, 0, null];
    }



}