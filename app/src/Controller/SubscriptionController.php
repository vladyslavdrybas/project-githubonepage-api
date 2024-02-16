<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Subscription;
use App\Entity\SubscriptionPlan;
use App\Repository\SubscriptionRepository;
use App\Security\Permissions;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/subscription', name: "api_subscription")]
class SubscriptionController extends AbstractController
{
    #[Route('/{subscription}', name: '_read', methods: ["GET"])]
    #[IsGranted(Permissions::READ, 'subscription', 'Access denied', Response::HTTP_UNAUTHORIZED)]
    public function read(
        Subscription $subscription
    ): Response {
        return $this->json($subscription);
    }

    #[Route('/plan/{subscriptionPlan}', name: '_plan', methods: ["GET"])]
    #[IsGranted(Permissions::READ, 'subscriptionPlan', 'Access denied', Response::HTTP_UNAUTHORIZED)]
    public function plan(
        SubscriptionPlan $subscriptionPlan
    ): Response {
        return $this->json($subscriptionPlan);
    }

    #[Route('/subscribe/{subscriptionPlan}', name: '_subscribe', methods: ["GET"])]
    public function subscribe(
        SubscriptionPlan $subscriptionPlan,
        SubscriptionRepository $repo
    ): Response {
        $subscription = new Subscription();

        $subscription->setSubscriber($this->getUser());
        $subscription->setSubscriptionPlan($subscriptionPlan);

//        $period = $subscriptionPlan->getPeriod();
//        $subscriptionEndDate = match($period) {
//            PeriodType::MONTH => new DateTime('+1 month'),
//            PeriodType::YEAR => new DateTime('+1 year'),
//            PeriodType::QUARTER => new DateTime('+3 months'),
//            default => null
//        };
//        $subscription->setEndDate($subscriptionEndDate);

        $repo->add($subscription);
        $repo->save();

        return $this->json($subscription);
    }
}
