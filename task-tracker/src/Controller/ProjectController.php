<?php

namespace App\Controller;

use App\Model;
use App\Storage\DataStorage;
use JsonException;
use Symfony\Component\HttpFoundation\{
    Request, Response, JsonResponse
};
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class ProjectController
{
    /**
     * @var DataStorage
     */
    private DataStorage $storage;

    public function __construct(DataStorage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @Route("/project/{id}", name="project", method="GET")
     * @param Request $request
     * @return JsonResponse
     */
    public function projectAction(Request $request): JsonResponse
    {
        try {
            $project = $this->storage->getProjectById((int)$request->get('id'));
            return new JsonResponse($project->toJson());
        } catch (Model\NotFoundException $e) {
            return new JsonResponse('Not found', Response::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            return new JsonResponse(
                'Something went wrong',
                Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        }
    }

    /**
     * @Route("/project/{id}/tasks", name="project-tasks", method="GET")
     * @param Request $request
     * @return JsonResponse
     * @throws JsonException
     */
    public function projectTaskPagerAction(Request $request): JsonResponse
    {
        $tasks = $this->storage->getTasksByProjectId(
            (int)$request->get('id'),
            (int)$request->get('limit') ?: 10,
            (int)$request->get('offset') ?: 0,
        );

        return new JsonResponse(json_encode($tasks, JSON_THROW_ON_ERROR));
    }

    /**
     * @Route("/project/{id}/tasks", name="project-create-task", method="PUT")
     * @param Request $request
     * @return JsonResponse
     * @throws Model\NotFoundException
     */
    public function projectCreateTaskAction(Request $request): JsonResponse
    {
        $project = $this->storage->getProjectById((int)$request->get('id'));
        if (!$project) {
            return new JsonResponse(['error' => 'Not found']);
        }

        return new JsonResponse(
            $this->storage->createTask($_REQUEST, $project->getId()),
            Response::HTTP_CREATED,
        );
    }
}
