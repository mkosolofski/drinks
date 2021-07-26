<?php
/**
 * Contains App\Controller\UserDrink
 *
 * @package App
 * @subpackage Controller
 * @author Matthew Kosolofski <matthew.kosolofski@gmail.com>
 */

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Configuration as ConfigurationEntity;
use App\Entity\Drink as DrinkEntity;
use App\Entity\User as UserEntity;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;

use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Operation;

use OpenApi\Annotations as OA;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @package App
 * @subpackage Controller
 * @author Matthew Kosolofski <matthew.kosolofski@gmail.com>
 */
class UserDrinkController extends AbstractFOSRestController
{
    /**
     * Add drinks to a user.
     *
     * @Rest\Post("/api/user/drinks")
     *
     * @Rest\View(serializerGroups={"post_read_userdrinks"})
     * 
     * @ParamConverter(
     *     "user",
     *     converter="fos_rest.request_body",
     *     options={
     *         "deserializationContext"={
     *             "groups"={"post_write_userdrinks"}
     *         },
     *         "validator"={
     *             "groups"={"post_write_userdrinks"}
     *         }
     *     }
     * )
     * 
     * @Operation(
     *     tags={"Drink"},
     *     summary="Add drinks to a user.",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 ref=@Model(type=UserEntity::class, groups={"post_write_userdrinks"})
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="Drink added.",
     *         @OA\JsonContent(
     *             ref=@Model(type=UserEntity::class, groups={"post_read_userdrinks"})
     *         )
     *     )
     * )
     *
     * @param UserEntity $user
     * @param TranslatorInterface $translator
     *
     * @return UserEntity
     */
    public function postUserAction(UserEntity $user, TranslatorInterface $translator): UserEntity
    {
        $doctrine = $this->getDoctrine();
        $doctrineManager = $doctrine->getManager();

        $doctrineManager->clear();

        $existingUser = $doctrine->getRepository(UserEntity::class)->find($user->getId());

        if (!$existingUser) {
            throw $this->createNotFoundException($translator->trans('user.not_found'));
        }

        $userDrinks = $user->getUserDrinks();
        if (empty($userDrinks)) return $existingUser;


        // How much caffeine the user currently has consumed.
        $currentCaffeine = $existingUser->getTotalDrinkCaffeine();


        // How much new caffeine the user wants to consume.
        $newCaffeine = 0;
        $newDrinks = $doctrine->getRepository(DrinkEntity::class)->findBy(['id' => $user->getDrinkIds()]);
        foreach($newDrinks as $newDrink) {
            $newCaffeine += $newDrink->getCaffeine();
        }


        // Total caffeine consumed with new drinks.
        $totalCaffeine = $currentCaffeine + $newCaffeine;


        // Check our caffeine limit.
        $caffeineLimit = $doctrine->getRepository(ConfigurationEntity::class)->findOneBy(['name' => 'max_caffeine_mg']);

        if ($totalCaffeine > $caffeineLimit->getValue()) {
            throw new AccessDeniedHttpException(
                $translator->trans(
                    'user.caffeine_limit_hit',
                    [
                        '{{ limit }}' => "{$caffeineLimit->getValue()} mg",
                        '{{ current }}' => $currentCaffeine
                    ]
                )
            );
        }


        // User has not reached their limits, save drink.
        foreach($user->getDrinks() as $drink) {
            $existingUser->addDrink(
                $doctrineManager->getReference(get_class($drink), $drink->getId())
            );
        }

        $doctrineManager->persist($existingUser);
        $doctrineManager->flush();

        return $existingUser;
    }
}
