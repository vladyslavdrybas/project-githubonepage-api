<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\SubscriptionPlan;
use App\Security\Permissions;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/subscription', name: "api_subscription")]
class SubscriptionController extends AbstractController
{
    #[Route('/plan/{subscription}', name: '_read', methods: ["GET"])]
    #[IsGranted(Permissions::READ, 'subscription', 'Access denied', Response::HTTP_UNAUTHORIZED)]
    public function read(
        SubscriptionPlan $subscription
    ): Response {
        return $this->json($subscription);
    }
}
