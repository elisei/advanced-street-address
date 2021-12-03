<?php
/**
 * Copyright © O2TI. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See COPYING.txt for license details.
 */

namespace O2TI\AdvancedStreetAddress\Model;

use O2TI\AdvancedStreetAddress\Helper\Config;

/**
 *  ChangesStreetLine - Change Compoments for Inputs.
 */
class ChangesStreetLine
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @param Config $config
     */
    public function __construct(
        Config $config
    ) {
        $this->config = $config;
    }

    /**
     * Change Components to Fields.
     *
     * @param array $fields
     *
     * @return array
     */
    public function changeComponentFields(array $fields): array
    {
        foreach ($fields as $key => $data) {
            if ($key == 'street') {
                $defaultPosition = (int) $fields[$key]['sortOrder'];

                $fields[$key]['config']['template'] = 'O2TI_AdvancedStreetAddress/form/element/addressline';
                $fields[$key]['config']['fieldTemplate'] = 'O2TI_AdvancedStreetAddress/form/field';

                foreach ($fields[$key]['children'] as $arrayPosition => $streetLine) {
                    $fields[$key]['children'][$arrayPosition]['sortOrder'] = $defaultPosition + $arrayPosition;

                    if ($this->config->getConfigForLabel($arrayPosition, 'use_label')) {
                        $labelStreet = $this->config->getConfigForLabel($arrayPosition, 'label');
                        $fields[$key]['children'][$arrayPosition]['label'] = __($labelStreet);
                    }

                    if ($isRequired = $this->config->getConfigForValidation($arrayPosition, 'is_number')) {
                        // phpcs:ignore
                        if ($fields[$key]['children'][$arrayPosition]['config']['elementTmpl'] === 'ui/form/element/input') {
                            // phpcs:ignore
                            $fields[$key]['children'][$arrayPosition]['config']['elementTmpl'] = 'O2TI_AdvancedStreetAddress/form/element/number';
                        // phpcs:ignore
                        } elseif ($fields[$key]['children'][$arrayPosition]['config']['elementTmpl'] === 'O2TI_AdvancedFieldsCheckout/form/element/input') {
                            $fields[$key]['children'][$arrayPosition]['config']['elementTmpl']
                                = 'O2TI_AdvancedStreetAddress/form/element/O2TI/AdvancedStreetAddress/number';
                        }
                    }

                    $isRequired = $this->config->getConfigForValidation($arrayPosition, 'is_required');
                    $maxLength = $this->config->getConfigForValidation($arrayPosition, 'max_length');
                    $fields[$key]['children'][$arrayPosition]['validation'] = [
                        'min_text_length' => 1,
                        'max_text_length' => $maxLength,
                    ];
                    if ($isRequired) {
                        $fields[$key]['children'][$arrayPosition]['validation'] = [
                            'required-entry'  => $isRequired,
                            'min_text_length' => 1,
                            'max_text_length' => $maxLength,
                        ];
                    }
                }
            }
        }

        return $fields;
    }
}
