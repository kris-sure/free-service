<?php
/**
 * Created by PhpStorm.
 * User: ktv911
 * Date: 05.11.19
 * Time: 11:33
 */

namespace App\Controller\Cabinet\Profile;

use App\Service\Cabinet\Profile\ProfileService;
use App\Service\User\UserService;
use App\Validation\Auth\ChangePasswordValidation;
use App\Validation\Cabinet\Profile\ChangeEmailValidation;
use App\Validation\Cabinet\Profile\CreateProfileValidation;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cabinet")
 */
class ProfileController extends AbstractController
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var ProfileService
     */
    private $profileService;


    public function __construct(UserService $userService, ProfileService $profileService)
    {
        $this->userService = $userService;
        $this->profileService = $profileService;
    }

    /**
     * Create user profile
     * @Route("/profile", name="cabinet_create_profile",  methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function create_profile(Request $request): JsonResponse
    {
        $data = $request->request->all();

        $violations = (new CreateProfileValidation())->validate($data);
        if ($violations->count() > 0) {
            return new JsonResponse(["error" => (string)$violations], 500);
        }

        try {
            $this->profileService->createdProfile($data, $this->getUser()->getId());
            return new JsonResponse("You have successfull filled your profile.", 201);
        } catch (\Exception $e) {
            return new JsonResponse(["error" => $e->getMessage()], 500);
        }
    }

    /**
     * Update user profile
     * @Route("/profile/{id}", name="cabinet_update_profile",  methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function update_profile(Request $request): JsonResponse
    {
        $data = $request->request->all();

        $violations = (new CreateProfileValidation())->validate($data);
        if ($violations->count() > 0) {
            return new JsonResponse(["error" => (string)$violations], 500);
        }

        try {
            $this->profileService->updateProfile($data, $request->get('id'), $this->getUser()->getId());
            return new JsonResponse("You have successfull update your profile.", 201);
        } catch (\Exception $e) {
            return new JsonResponse(["error" => $e->getMessage()], 500);
        }
    }


    /**
     * Change user email
     * @Route("/profile/{id}/change-email", name="cabinet_change_email",  methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function change_email(Request $request): JsonResponse
    {
        $data = $request->request->all();

        $violations = (new ChangeEmailValidation())->validate($data);
        if ($violations->count() > 0) {
            return new JsonResponse(["error" => (string)$violations], 500);
        }

        try {
            $this->userService->changeEmail($data, $request->get('id'), $this->getUser()->getId());
            return new JsonResponse("You have successfull changed your email.", 200);
        } catch (\Exception $e) {
            return new JsonResponse(["error" => $e->getMessage()], 500);
        }
    }

    /**
     * Change user password
     * @Route("/profile/{id}/change-password", name="cabinet_change_password",  methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function change_password(Request $request): JsonResponse
    {
        $data = $request->request->all();

        $violations = (new ChangePasswordValidation())->validate($data);
        if ($violations->count() > 0) {
            return new JsonResponse(["error" => (string)$violations], 500);
        }

        try {
            $this->userService->changePassword($data, $request->get('id'), $this->getUser()->getId());
            return new JsonResponse("You have successfull changed your password.", 200);
        } catch (\Exception $e) {
            return new JsonResponse(["error" => $e->getMessage()], 500);
        }
    }

}