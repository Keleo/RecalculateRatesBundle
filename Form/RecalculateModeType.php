<?php

/*
 * This file is part of the RecalculateRatesBundle for Kimai.
 * All rights reserved by Kevin Papst (www.keleo.de).
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KimaiPlugin\RecalculateRatesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecalculateModeType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'required' => true,
            'multiple' => false,
            'label' => 'kiosk.login_type',
            'choices' => [
                'recalculate.mode.off' => null,
                'recalculate.mode.default' => 'default',
                'recalculate.mode.always' => 'always'
            ],
        ]);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
