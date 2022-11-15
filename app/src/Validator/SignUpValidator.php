<?php

namespace App\Validator;

use App\DTO\SignUp\SignUpDTO;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class SignUpValidator extends ConstraintValidator
{
    private const NON_EQUAL_PASSWORDS = 'Passwords non equal!';
    private const NON_STRONG_PASSWORD = 'Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.';
    private const NON_UNIQUE_EMAIL = 'There is user with such email!';

    public function __construct(
        private EntityManagerInterface $em,
    ){}

    /**
     * @param SignUpDTO $value
     */
    public function validate($value, Constraint $constraint): void
    {
        if ($value->getPassword() !== $value->getRepeatPassword()){
            $this->context->buildViolation(self::NON_EQUAL_PASSWORDS)
                ->addViolation();
        }
        if ($this->checkPasswordStrength($value->getPassword())){
            $this->context->buildViolation(self::NON_STRONG_PASSWORD)
                ->addViolation();
        }
        if ($this->em->getRepository(User::class)->findOneBy(['email' => $value->getEmail()])){
            $this->context->buildViolation(self::NON_UNIQUE_EMAIL)
                ->addViolation();
        }
    }

    private function checkPasswordStrength(string $password): bool
    {
//        $uppercase = preg_match('@[A-Z]@', $password);
//        $lowercase = preg_match('@[a-z]@', $password);
//        $number    = preg_match('@[0-9]@', $password);
//        $specialChars = preg_match('@[^\w]@', $password);
//        return (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8);
        return false;
    }
}
