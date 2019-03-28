<?php

namespace App\Controller\Resource;

use App\Document\ResetPasswordErrorDocument;
use App\Document\UserDocument;
use App\Document\UsersDocument;
use App\Entity\User;
use App\Factory\ErrorsFactory;
use App\Factory\ResetPasswordErrorFactory;
use App\Hydrator\UserHydrator;
use App\Repository\UserRepository;
use App\Service\UserService;
use App\Transformer\UserTransformer;
use Aristek\Bundle\ExtraBundle\Exception\AppException;
use Aristek\Bundle\SymfonyJSONAPIBundle\Controller\AbstractController;
use Aristek\Bundle\SymfonyJSONAPIBundle\Service\Filter\ResourceProvider;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class UserController
 *
 * @Route("/users")
 */
class UserController extends AbstractController
{
    /**
     * @Route("", name="users_index", methods="GET")
     *
     * @param Request          $request
     * @param ResourceProvider $resourceProvider
     * @param UserRepository   $userRepository
     * @param UserTransformer  $transformer
     *
     * @return ResponseInterface
     */
    public function index(
        Request $request,
        ResourceProvider $resourceProvider,
        UserRepository $userRepository,
        UserTransformer $transformer
    ): ResponseInterface {
        return $this->jsonApi()->respond()->ok(
            new UsersDocument($transformer, $this->router),
            $resourceProvider->getResources($request, $this->generateQuery($userRepository, $request))
        );
    }

    /**
     * @Route("", name="users_new", methods="POST")
     *
     * @param UserHydrator       $hydrator
     * @param UserRepository     $userRepository
     * @param UserTransformer    $transformer
     * @param ValidatorInterface $validator
     *
     * @return ResponseInterface
     */
    public function new(
        UserHydrator $hydrator,
        UserRepository $userRepository,
        UserTransformer $transformer,
        ValidatorInterface $validator
    ): ResponseInterface {
        $user = $this->jsonApi()->hydrate($hydrator, $userRepository->create());

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($user);
        if ($errors->count() > 0) {
            return $this->validationErrorResponse($errors);
        }

        $userRepository->save($user);

        return $this->jsonApi()->respond()->ok(new UserDocument($transformer, $this->router), $user);
    }

    /**
     * @Route("/{id}", name="users_show", methods="GET", requirements={"id"="\d+"})
     *
     * @param User            $user
     * @param UserTransformer $transformer
     *
     * @return ResponseInterface
     */
    public function show(User $user, UserTransformer $transformer): ResponseInterface
    {
        return $this->jsonApi()->respond()->ok(new UserDocument($transformer, $this->router), $user);
    }

    /**
     * @Route("/{id}", name="users_edit", methods="PATCH", requirements={"id"="\d+"})
     *
     * @param User               $user
     * @param UserHydrator       $hydrator
     * @param UserRepository     $userRepository
     * @param UserTransformer    $transformer
     * @param ValidatorInterface $validator
     *
     * @return ResponseInterface
     */
    public function edit(
        User $user,
        UserHydrator $hydrator,
        UserRepository $userRepository,
        UserTransformer $transformer,
        ValidatorInterface $validator
    ): ResponseInterface {
        $user = $this->jsonApi()->hydrate($hydrator, $user);

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($user);
        if ($errors->count() > 0) {
            return $this->validationErrorResponse($errors);
        }

        $userRepository->save($user);

        return $this->jsonApi()->respond()->ok(new UserDocument($transformer, $this->router), $user);
    }

    /**
     * @Route("/{id}", name="users_delete", methods="DELETE", requirements={"id"="\d+"})
     *
     * @param User           $user
     * @param UserRepository $userRepository
     *
     * @return ResponseInterface
     */
    public function delete(User $user, UserRepository $userRepository): ResponseInterface
    {
        $userRepository->remove($user);

        return $this->jsonApi()->respond()->genericSuccess(204);
    }

    /**
     * @Route("/{id}/reset-password", methods="GET", requirements={"id"="\d+"})
     *
     * @param User                      $user
     * @param ErrorsFactory             $errorsFactory
     * @param ResetPasswordErrorFactory $resetPasswordErrorFactory
     * @param UserService               $userService
     *
     * @return ResponseInterface
     */
    public function resetPassword(
        User $user,
        ErrorsFactory $errorsFactory,
        ResetPasswordErrorFactory $resetPasswordErrorFactory,
        UserService $userService
    ): ResponseInterface {
        try {
            $userService->resetPassword($user);
        } /** @noinspection PhpRedundantCatchClauseInspection */ catch (AppException $exception) {
            return $this->jsonApi()->respond()->genericError(
                new ResetPasswordErrorDocument($user->getId()),
                $errorsFactory->create($resetPasswordErrorFactory, [$exception->getMessage()])
            );
        }

        return $this->jsonApi()->respond()->noContent();
    }
}
