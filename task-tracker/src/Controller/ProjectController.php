<?php

namespace Api\Controller;

use App\Model;
use App\Storage\DataStorage;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController
{
  /**
   * @var DataStorage
   */
  private $storage;

  public function __construct(DataStorage $storage)
  {
    $this->storage = $storage;
  }

  /**
   * @param Request $request
   *
   * @Route("/project/{id}", name="project", method="GET")
   */
  public function projectAction(Request $request)
  {
    try {
      $project = $this->storage->getProjectById($request->get('id'));

      return new Response($project->toJson());
    } catch (Model\NotFoundException $e) {
      return new Response('Not found', 404);
    } catch (\Throwable $e) {
      return new Response('Something went wrong', 500);
    }
  }

  /**
   * @param Request $request
   *
   * @Route("/project/{id}/tasks", name="project-tasks", method="GET")
   */
  public function projectTaskPagerAction(Request $request)
  {
    $tasks = $this->storage->getTasksByProjectId(
      $request->get('id'),
      $request->get('limit'),
      $request->get('offset')
    );

    return new Response(json_encode($tasks));
  }

  /**
   * @param Request $request
   *
   * @Route("/project/{id}/tasks", name="project-create-task", method="PUT")
   */
  public function projectCreateTaskAction(Request $request)
  {
    $project = $this->storage->getProjectById($request->get('id'));
    if (!$project) {
      return new JsonResponse(['error' => 'Not found']);
    }

    return new JsonResponse(
      $this->storage->createTask($_REQUEST, $project->getId())
    );
  }
}
