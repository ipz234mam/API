<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1')]
class User_Controller extends AbstractController
{
    public const STAFF_MEMBERS = [
        [
            'id'    => '1',
            'email' => 'staff1@example.com',
            'name'  => 'Michael'
        ],
        [
            'id'    => '2',
            'email' => 'worker2@example.com',
            'name'  => 'Sarah'
        ],
        [
            'id'    => '3',
            'email' => 'team3@example.com',
            'name'  => 'Robert'
        ],
        [
            'id'    => '4',
            'email' => 'associate4@example.com',
            'name'  => 'Emily'
        ],
        [
            'id'    => '5',
            'email' => 'member5@example.com',
            'name'  => 'Daniel'
        ],
    ];

    #[Route('/staff', name: 'get_all_staff', methods: ['GET'])]
    #[IsGranted("ROLE_ADMIN")]
    public function fetchAllStaff(): JsonResponse
    {
        return new JsonResponse([
            'data' => self::STAFF_MEMBERS
        ], Response::HTTP_OK);
    }

    #[Route('/staff/{id}', name: 'get_single_staff', methods: ['GET'])]
    public function retrieveStaffMember(string $id): JsonResponse
    {
        $staffData = $this->searchStaffById($id);

        return new JsonResponse([
            'data' => $staffData
        ], Response::HTTP_OK);
    }

    #[Route('/staff', name: 'add_new_staff', methods: ['POST'])]
    public function registerStaff(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        if (!isset($requestData['email'], $requestData['name'])) {
            throw new UnprocessableEntityHttpException("Name and email are required");
        }

        $staffCount = count(self::STAFF_MEMBERS);

        $newStaff = [
            'id'    => $staffCount + 1,
            'name'  => $requestData['name'],
            'email' => $requestData['email']
        ];

        return new JsonResponse([
            'data' => $newStaff
        ], Response::HTTP_CREATED);
    }

    #[Route('/staff/{id}', name: 'remove_staff', methods: ['DELETE'])]
    public function deleteStaff(string $id): JsonResponse
    {
        $this->searchStaffById($id);

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }

    #[Route('/staff/{id}', name: 'modify_staff', methods: ['PATCH'])]
    public function updateStaff(string $id, Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        if (!isset($requestData['name'])) {
            throw new UnprocessableEntityHttpException("Name is required");
        }

        $staffData = $this->searchStaffById($id);

        $staffData['name'] = $requestData['name'];

        return new JsonResponse(['data' => $staffData], Response::HTTP_OK);
    }

    public function searchStaffById(string $id): array
    {
        $staffData = null;

        foreach (self::STAFF_MEMBERS as $member) {
            if (!isset($member['id'])) {
                continue;
            }

            if ($member['id'] == $id) {
                $staffData = $member;
                break;
            }
        }

        if (!$staffData) {
            throw new NotFoundHttpException("Staff member with id " . $id . " not found");
        }

        return $staffData;
    }
}