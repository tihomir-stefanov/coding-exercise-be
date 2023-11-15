<?php

namespace App\Tests\Form;

use App\Entity\User;
use App\Form\UserFormType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;

class UserFormTypeTest extends TypeTestCase
{
    //load extension for constraints
    protected function getExtensions()
    {
        return [new ValidatorExtension(Validation::createValidator())];
    }

    public function testSubmitValidData(): void
    {
        $formData = [
            'firstName' => 'Marry',
            'lastName' => 'Lindt',
            'email' => 'marrylindt@notgmail.com'
        ];

        $model = new User();

        $form = $this->factory->create(UserFormType::class, $model);

        $expected = new User();
        $expected->setFirstName('Marry');
        $expected->setLastName('Lindt');
        $expected->setEmail('marrylindt@notgmail.com');

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($expected, $model);
    }
}
