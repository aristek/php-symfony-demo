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
use Aristek\Bundle\SymfonyJSONAPIBundle\JsonApi\Error\ErrorDocumentFactory;
use Aristek\Bundle\SymfonyJSONAPIBundle\Service\Filter\ResourceProvider;
use Aristek\Bundle\SymfonyJSONAPIBundle\Service\WrongFieldsLogger;
use Paknahad\JsonApiBundle\Helper\Filter\FinderCollection;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
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
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var UserTransformer
     */
    private $userTransformer;

    /**
     * @var UserHydrator
     */
    private $userHydrator;

    /**
     * UserController constructor.
     *
     * @param FinderCollection     $finderCollection
     * @param WrongFieldsLogger    $wrongFieldsLogger
     * @param RouterInterface      $router
     * @param ErrorDocumentFactory $errorDocumentFactory
     * @param UserRepository       $userRepository
     * @param UserTransformer      $userTransformer
     * @param UserHydrator         $userHydrator
     */
    public function __construct(
        FinderCollection $finderCollection,
        WrongFieldsLogger $wrongFieldsLogger,
        RouterInterface $router,
        ErrorDocumentFactory $errorDocumentFactory,
        UserRepository $userRepository,
        UserTransformer $userTransformer,
        UserHydrator $userHydrator
    ) {
        parent::__construct($finderCollection, $wrongFieldsLogger, $router, $errorDocumentFactory);

        $this->userRepository = $userRepository;
        $this->userTransformer = $userTransformer;
        $this->userHydrator = $userHydrator;
    }

    /**
     * @Route("", name="users_index", methods="GET")
     *
     * @param Request          $request
     * @param ResourceProvider $resourceProvider
     *
     * @return ResponseInterface
     */
    public function index(Request $request, ResourceProvider $resourceProvider): ResponseInterface
    {
        return $this->jsonApi()->respond()->ok(
            new UsersDocument($this->userTransformer, $this->router),
            $resourceProvider->getResources($request, $this->generateQuery($this->userRepository, $request))
        );
    }

    /**
     * @Route("", name="users_new", methods="POST")
     *
     * @param ValidatorInterface $validator
     *
     * @return ResponseInterface
     */
    public function new(ValidatorInterface $validator): ResponseInterface
    {
        $user = $this->jsonApi()->hydrate($this->userHydrator, $this->userRepository->create());

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($user);
        if ($errors->count()) {
            return $this->validationErrorResponse($errors);
        }

        $this->userRepository->save($user);

        return $this->jsonApi()->respond()->ok(new UserDocument($this->userTransformer, $this->router), $user);
    }

    /**
     * @Route("/{id}", name="users_show", methods="GET", requirements={"id"="\d+"})
     *
     * @param User $user
     *
     * @return ResponseInterface
     */
    public function show(User $user): ResponseInterface
    {
        return $this->jsonApi()->respond()->ok(new UserDocument($this->userTransformer, $this->router), $user);
    }

    /**
     * @Route("/{id}", name="users_edit", methods="PATCH", requirements={"id"="\d+"})
     *
     * @param User               $user
     * @param ValidatorInterface $validator
     *
     * @return ResponseInterface
     */
    public function edit(User $user, ValidatorInterface $validator): ResponseInterface
    {
        $user = $this->jsonApi()->hydrate($this->userHydrator, $user);

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($user);
        if ($errors->count() > 0) {
            return $this->validationErrorResponse($errors);
        }

        $this->userRepository->save($user);

        return $this->jsonApi()->respond()->ok(new UserDocument($this->userTransformer, $this->router), $user);
    }

    /**
     * @Route("/{id}", name="users_delete", methods="DELETE", requirements={"id"="\d+"})
     *
     * @param User $user
     *
     * @return ResponseInterface
     */
    public function delete(User $user): ResponseInterface
    {
        $this->userRepository->remove($user);

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
