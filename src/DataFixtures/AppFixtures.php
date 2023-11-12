<?php

namespace App\DataFixtures;

use App\Entity\Appointment;
use App\Entity\AppointmentFeedback;
use App\Entity\Award;
use App\Entity\Klass;
use App\Entity\Lesson;
use App\Entity\Student;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private const PASSWORD = '$2y$13$.mYMgJW629WxHm4Tnxz3FuPqN20SvZ6bTyjKzOT0ZWYI9bLmJDvhq'; // 123123

    public function load(ObjectManager $manager): void
    {
        $user1 = new User();
        $user1->setEmail('john@lingoda.com');
        $user1->setFirstName('John');
        $user1->setLastName('Doe');
        $user1->setPassword(self::PASSWORD);
        $manager->persist($user1);

        $student1 = new Student();
        $student1->setUser($user1);
        $student1->setLevel(7);
        $manager->persist($student1);

        $user2 = new User();
        $user2->setEmail('jane@lingoda.com');
        $user2->setFirstName('Jane');
        $user2->setLastName('Doe');
        $user2->setPassword(self::PASSWORD);
        $manager->persist($user2);

        $student2 = new Student();
        $student2->setUser($user2);
        $student2->setLevel(2);
        $manager->persist($student2);

        for ($i = 0; $i < 10; $i++) {
            $lesson = new Lesson();
            $lesson->setTitle('Lesson ' . $i);
            $manager->persist($lesson);

            $klass = new Klass();
            $klass->setLesson($lesson);
            $klass->setStartDate(new \DateTime());
            $manager->persist($klass);

            $appointment = new Appointment();
            $appointment->setKlass($klass);
            $appointment->setStudent($student1);
            $manager->persist($appointment);

            $feedback = new AppointmentFeedback();
            $feedback->setAppointment($appointment);
            $feedback->setText('Feedback for appointment ' . $i);
            $manager->persist($feedback);

        }

        for ($i = 20; $i < 25; $i++) {
            $lesson = new Lesson();
            $lesson->setTitle('Lesson ' . $i);
            $manager->persist($lesson);

            $klass = new Klass();
            $klass->setLesson($lesson);
            $klass->setStartDate(new \DateTime());
            $manager->persist($klass);

            $appointment = new Appointment();
            $appointment->setKlass($klass);
            $appointment->setStudent($student2);
            $manager->persist($appointment);

            $feedback = new AppointmentFeedback();
            $feedback->setAppointment($appointment);
            $feedback->setText('Feedback for appointment ' . $i);
            $manager->persist($feedback);
        }

        for ($i = 1; $i < 11; $i++) {
            $award = new Award();
            $award->setName('Award ' . $i);
            $award->setText('You won price for level: ' . $i);
            $award->setLevel($i);
            $manager->persist($award);
        }

        $manager->flush();
    }

}
