<?php
/**
 * Contains App\Controller\User
 *
 * @package App
 * @subpackage Controller
 * @author Matthew Kosolofski <matthew.kosolofski@gmail.com>
 */

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User as UserEntity;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;

use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Operation;

use OpenApi\Annotations as OA;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @package App
 * @subpackage Controller
 * @author Matthew Kosolofski <matthew.kosolofski@gmail.com>
 */
class UserController extends AbstractFOSRestController
{
    /**
     * Get all users.
     *
     * @Rest\Get("/api/users")
     * @Rest\View(serializerGroups={"get_read_users"})
     * 
     * @Operation(
     *     tags={"User"},
     *     summary="Get all users.",
     *     @OA\Response(
     *         response="200",
     *         description="All users.",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref=@Model(type=UserEntity::class, groups={"get_read_user"})
)
     *         )
     *     )
     * )
     *
     * @return UserEntity[]
     */
    public function getUserIdsAction(): array
    {
        return $this->getDoctrine()
            ->getRepository(UserEntity::class)
            ->findBy([], ['name' => 'ASC']);
    }

    /**
     * Get user.
     *
     * @Rest\Get(
     *     "/api/user/{userId}",
     *     requirements={"userId"="^[1-9]([0-9])*$"}
     * )
     * @Rest\View(serializerGroups={"get_read_user"})
     * 
     * @Operation(
     *     tags={"User"},
     *     summary="Get user.",
     *     @OA\Response(
     *         response="200",
     *         description="The user.",
     *         @OA\JsonContent(
     *             ref=@Model(type=UserEntity::class, groups={"get_read_user"})
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="User not found."
     *     )
     * )
     *
     * @param int $userId
     * @param TranslatorInterface $translator
     *
     * @return UserEntity
     */
    public function getUserAction(int $userId, TranslatorInterface $translator): UserEntity
    {
        $user = $this->getDoctrine()
            ->getRepository(UserEntity::class)
            ->find($userId);

        if (!$user) {
            throw $this->createNotFoundException($translator->trans('user.not_found'));
        }

        return $user;
    }

    /**
     * Create user.
     *
     * @Rest\Post("/api/user")
     *
     * @Rest\View(serializerGroups={"post_read_user"})
     * 
     * @ParamConverter(
     *     "user",
     *     converter="fos_rest.request_body",
     *     options={
     *         "deserializationContext"={
     *             "groups"={"post_write_user"}
     *         },
     *         "validator"={
     *             "groups"={"post_write_user"}
     *         }
     *     }
     * )
     * 
     * @Operation(
     *     tags={"User"},
     *     summary="Create user.",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 ref=@Model(type=UserEntity::class, groups={"post_write_user"})
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="User created.",
     *         @OA\JsonContent(
     *             ref=@Model(type=UserEntity::class, groups={"post_read_user"})
     *         )
     *     )
     * )
     *
     * @param UserEntity $user
     *
     * @return UserEntity
     */
    public function postUserAction(UserEntity $user): UserEntity
    {
        $doctrine = $this->getDoctrine();
        $doctrineManager = $doctrine->getManager();
        
        foreach($user->getUserDrinks() as $userDrink) {
            $userDrink->setUser($user);
            $userDrink->setDrink(
                $doctrineManager->getReference(get_class($userDrink->getDrink()), $userDrink->getDrink()->getId())
            );
        }

        $doctrineManager->persist($user);
        $doctrineManager->flush();
        
        return $user;
    }
}
