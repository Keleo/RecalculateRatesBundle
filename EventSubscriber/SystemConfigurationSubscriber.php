<?php

/*
 * This file is part of the RecalculateRatesBundle for Kimai.
 * All rights reserved by Kevin Papst (www.keleo.de).
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KimaiPlugin\RecalculateRatesBundle\EventSubscriber;

use App\Event\SystemConfigurationEvent;
use App\Form\Model\Configuration;
use App\Form\Model\SystemConfiguration;
use KimaiPlugin\RecalculateRatesBundle\Form\RecalculateModeType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class SystemConfigurationSubscriber implements EventSubscriberInterface
{
    /**
     * @return array<string, array<int, string|int>>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            SystemConfigurationEvent::class => ['onSystemConfiguration', 100],
        ];
    }

    public function onSystemConfiguration(SystemConfigurationEvent $event): void
    {
        $newConfiguration = (new Configuration())
            ->setName('timesheet.recalculate.mode')
            ->setLabel('recalculate.mode')
            ->setRequired(true)
            ->setTranslationDomain('system-configuration')
            ->setType(RecalculateModeType::class);

        foreach ($event->getConfigurations() as $configuration) {
            if ($configuration->getSection() === 'timesheet') {
                $configuration->addConfiguration($newConfiguration);

                return;
            }
        }

        $event->addConfiguration(
            (new SystemConfiguration('recalculate'))->setConfiguration([$newConfiguration])
        );
    }
}
