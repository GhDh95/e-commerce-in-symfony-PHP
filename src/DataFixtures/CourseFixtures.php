<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Course;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
class CourseFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        require_once 'vendor/autoload.php';
        $faker = Faker\Factory::create();
        // $product = new Product();
        // $manager->persist($product);

        for($i = 0; $i < 10; $i++){
            $category = new Category();
            $category->setName($faker->word());
            $course = new Course();
            $course->setTitle($faker->word());
            $course->setDescription($faker->text());
            $course->setCategory($category);
            $manager->persist($category);
            $manager->persist($course);
            $manager->flush();
        }
    }
}
