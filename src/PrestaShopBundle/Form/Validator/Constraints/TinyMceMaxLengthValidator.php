<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace PrestaShopBundle\Form\Validator\Constraints;

use PrestaShop\PrestaShop\Adapter\LegacyContext;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TinyMceMaxLengthValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param TinyMceMaxLength $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $replaceArray = [
            "\n",
            "\r",
            "\n\r",
        ];
        $str = str_replace($replaceArray, [''], strip_tags($value));
        $length = iconv_strlen($str);

        if ($length > $constraint->max) {
            $this->context->addViolation(
                (new LegacyContext())->getContext()->getTranslator()->trans('This value is too long. It should have %limit% characters or less.', [], 'Admin.Catalog.Notification'),
                ['%limit%' => $constraint->max]
            );
        }
    }
}
