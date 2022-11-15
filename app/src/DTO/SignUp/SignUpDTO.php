<?php

namespace App\DTO\SignUp;

use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as AcmeAssert;

#[AcmeAssert\SignUp]
class SignUpDTO
{
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email = null;

    #[Assert\NotBlank]
    private ?string $password = null;

    #[Assert\NotBlank]
    private ?string $repeatPassword = null;

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @return string|null
     */
    public function getRepeatPassword(): ?string
    {
        return $this->repeatPassword;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @param string|null $password
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    /**
     * @param string|null $repeatPassword
     */
    public function setRepeatPassword(?string $repeatPassword): void
    {
        $this->repeatPassword = $repeatPassword;
    }




}