<?php

namespace App\Controller\Resource;

use App\Document\UserDocument;
use App\Document\UsersDocument;
use App\Entity\User;
use App\Factory\UserFactory;
use App\Hydrator\UserHydrator;
use App\Repository\UserRepository;
use App\Service\UserService;
use Aristek\Bundle\JSONAPIBundle\Controller\AbstractController;
use Aristek\Bundle\JSONAPIBundle\Entity\File\File;
use Aristek\Bundle\JSONAPIBundle\JsonApi\Error\ErrorDocumentFactory;
use Aristek\Bundle\JSONAPIBundle\Service\Filter\ResourceProvider;
use Aristek\Bundle\JSONAPIBundle\Service\WrongFieldsLogger;
use Aristek\Bundle\JSONAPIBundle\Transformer\TransformerRegistry;
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
     * @var UserFactory
     */
    private $userFactory;

    /**
     * @var UserHydrator
     */
    private $userHydrator;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var TransformerRegistry
     */
    private $registry;

    /**
     * UserController constructor.
     *
     * @param FinderCollection     $finderCollection
     * @param WrongFieldsLogger    $wrongFieldsLogger
     * @param RouterInterface      $router
     * @param ErrorDocumentFactory $errorDocumentFactory
     * @param UserRepository       $userRepository
     * @param UserFactory          $userFactory
     * @param UserHydrator         $userHydrator
     * @param ValidatorInterface   $validator
     * @param TransformerRegistry  $registry
     */
    public function __construct(
        FinderCollection $finderCollection,
        WrongFieldsLogger $wrongFieldsLogger,
        RouterInterface $router,
        ErrorDocumentFactory $errorDocumentFactory,
        UserRepository $userRepository,
        UserFactory $userFactory,
        UserHydrator $userHydrator,
        ValidatorInterface $validator,
        TransformerRegistry $registry
    ) {
        parent::__construct($finderCollection, $wrongFieldsLogger, $router, $errorDocumentFactory);

        $this->userFactory = $userFactory;
        $this->userHydrator = $userHydrator;
        $this->userRepository = $userRepository;
        $this->validator = $validator;
        $this->registry = $registry;
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
            new UsersDocument($this->registry->getTransformer('users'), $this->urlGenerator),
            $resourceProvider->getResources($request, $this->generateQuery($this->userRepository, $request))
        );
    }

    /**
     * @Route("", name="users_new", methods="POST")
     *
     * @return ResponseInterface
     */
    public function new(): ResponseInterface
    {
        return $this->edit($this->userFactory->create());
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
        return $this->jsonApi()->respond()->ok(
            new UserDocument($this->registry->getTransformer('users'), $this->urlGenerator),
            $user
        );
    }

    /**
     * @Route("/{id}", name="users_edit", methods="PATCH", requirements={"id"="\d+"})
     *
     * @param User $user
     *
     * @return ResponseInterface
     */
    public function edit(User $user): ResponseInterface
    {
        $user = $this->jsonApi()->hydrate($this->userHydrator, $user);

        /** @var ConstraintViolationList $errors */
        $errors = $this->validator->validate($user);
        if ($errors->count()) {
            return $this->validationErrorResponse($errors);
        }

        $this->userRepository->save($user);
        // We need to refresh Entity in case of Related Collections
        $this->get('doctrine.orm.default_entity_manager')->refresh($user);

        return $this->show($user);
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

        return $this->jsonApi()->respond()->noContent();
    }

    /**
     * @Route("/{id}/reset-password", methods="GET", requirements={"id"="\d+"})
     *
     * @param User        $user
     * @param UserService $userService
     *
     * @return ResponseInterface
     */
    public function resetPassword(User $user, UserService $userService): ResponseInterface
    {
        $userService->resetPassword($user);

        return $this->jsonApi()->respond()->noContent();
    }

    /**
     * @Route("/{file}.{extension}", name="users_avatar")
     *
     * @param File   $file
     * @param string $extension
     *
     * @return void
     */
    public function avatar(File $file, string $extension): void
    {
        dd($file, $extension);
    }
}
