<?php

/*
 * This file is part of the RecalculateRatesBundle for Kimai.
 * All rights reserved by Kevin Papst (www.keleo.de).
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KimaiPlugin\RecalculateRatesBundle\Form;

use App\Constants;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecalculateModeType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $modes = ['recalculate.mode.off' => null];

        /* @phpstan-ignore-next-line */
        if (\defined('App\Constants::VERSION_ID') && Constants::VERSION_ID > 12000) {
            $modes['recalculate.mode.default'] = 'default';
        }

        $modes['recalculate.mode.always'] = 'always';

        $resolver->setDefaults([
            'required' => true,
            'multiple' => false,
            'label' => 'kiosk.login_type',
            'choices' => $modes,
        ]);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
