<?php

namespace App\DataFixtures\Processor;

use Fidry\AliceDataFixtures\ProcessorInterface;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;

class UserProcessor implements ProcessorInterface
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher, private PhoneNumberUtil $phoneNumberUtil)
    {
    }

    public function preProcess($id, $object): void
    {
        if (!$object instanceof User) {
            return;
        }

        $object->setPhoneNumberObject($this->phoneNumberUtil->parse($object->phoneNumber, 'FR'));

        $password = $this->passwordHasher->hashPassword($object, $object->getPassword());
        $object->setPassword($password);
    }

    public function postProcess($id, $object):void
    {

    }
}