<?php

declare(strict_types=1);

namespace MageSuite\Gdpr\Block\Role\Tab;

class Info extends \Magento\User\Block\Role\Tab\Info
{
    public const GDPR_FIELD = 'gdpr_access';

    public function __construct(
        protected \MageSuite\Gdpr\Helper\CustomerDataVisibility $customerDataVisibility,
        protected \MageSuite\Gdpr\Model\Authorization\CanChangeGdprAccess $canChangeConfigs,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
    }

    protected function _initForm() // phpcs:ignore
    {
        parent::_initForm();

        if (!$this->canChangeConfigs->execute()) {
            return;
        }

        $form = $this->getForm();

        $gdprFieldset = $form->addFieldset(
            'gdpr_access_fieldset',
            ['legend' => __('GDPR Access')]
        );
        $gdprFieldset->addField(
            self::GDPR_FIELD,
            'select',
            [
                'name' => self::GDPR_FIELD,
                'label' => __('Allow Access To Sensitive Data'),
                'id' => self::GDPR_FIELD,
                'title' => __('Allow Access To Sensitive Data'),
                'required' => true,
                'value' => $this->getRole()->getData(self::GDPR_FIELD) ?? 0,
                'options' => [0 => __('No'), 1 => __('Yes')],
            ]
        );

        $this->setForm($form);
    }
}
