<?php

namespace App\Controller;

use App\Entity\Course;
use App\Repository\CategoryRepository;
use App\Repository\CourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CoursesController extends AbstractController
{
    #[Route('/courses', name: 'app_courses')]
    public function index(CourseRepository $courseRepository, RequestStack  $requestStack, CategoryRepository $categoryRepository): Response
    {
        //get query param
        $query_parameters = $requestStack->getCurrentRequest()->query->all();
        $courses = $courseRepository->findCourses($query_parameters);

        //get all categories
        $categories = $categoryRepository->findAll();

        return $this->render('courses/index.html.twig', [
            'courses' => $courses,
            'categories' => $categories
        ]);
    }

    #[Route('/course/{id}', name: 'app_course')]
    public function show(Course $course): Response
    {
        return $this->render('courses/show.html.twig', [
            'course' => $course
        ]);
    }
}
