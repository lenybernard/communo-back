<?php

namespace App\Controller\Material\Booking;

use App\Entity\Material\Booking\MaterialBooking;
use App\Entity\Material\Material;
use App\Entity\User;
use App\Handler\Material\Pricing\EstimatePriceHandler;
use App\Repository\MaterialBookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Component\Workflow\Registry;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsController]
class Validate extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private Registry $workflowRegistry, private TranslatorInterface $translator, private MailerInterface $mailer, private string $defaultSender)
    {
    }

    public function __invoke(MaterialBooking $data, Request $request): MaterialBooking
    {
        $material = $data->getMaterial();
        $this->workflowRegistry->get($data, 'material_booking')->apply($data, MaterialBooking::TRANSITION_VALIDATE);
        $this->entityManager->flush();

        $email = (new NotificationEmail())
            ->from(new Address($this->defaultSender, 'Communo'))
            ->to(new Address($material?->getOwner()?->getEmail(), $material?->getOwner()?->getFirstname()))
            ->subject($this->translator->trans('material.booking.validate.title', ['material' => $material?->getName()]))
            ->htmlTemplate('emails/validate.html.twig')
            ->textTemplate('emails/validate.txt.twig')
            ->context([
                'footer_text' => 'Communo'
            ])
            ->action($this->translator->trans('material.booking.validate.action.label'), '#')
            ->importance(NotificationEmail::IMPORTANCE_HIGH)
            ->context([
                'booking' => $data,
            ])
        ;
        $this->mailer->send($email);

        return $data;
    }
}