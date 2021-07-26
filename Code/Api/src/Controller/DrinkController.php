<?php
/**
 * Contains App\Controller\Drink
 *
 * @package App
 * @subpackage Controller
 * @author Matthew Kosolofski <matthew.kosolofski@gmail.com>
 */

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Drink as DrinkEntity;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;

use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Operation;

use OpenApi\Annotations as OA;
/**
 * @package App
 * @subpackage Controller
 * @author Matthew Kosolofski <matthew.kosolofski@gmail.com>
 */
class DrinkController extends AbstractFOSRestController
{
    /**
     * Get all drinks.
     *
     * @Rest\Get("/api/drinks")
     * @Rest\View(serializerGroups={"get_read_drinks"})
     * 
     * @Operation(
     *     tags={"Drink"},
     *     summary="Get all drinks.",
     *     @OA\Response(
     *         response="200",
     *         description="A list of available drinks.",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref=@Model(type=DrinkEntity::class, groups={"get_read_drinks"}))
     *         )
     *     )
     * )
     *
     * @return DrinkEntity[]
     */
    public function getDrinksAction(): array
    {
        return $this->getDoctrine()
            ->getRepository(DrinkEntity::class)
            ->findBy([], ['name' => 'asc']);
    }
}
