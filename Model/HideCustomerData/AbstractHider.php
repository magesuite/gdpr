<?php

declare(strict_types=1);

namespace MageSuite\Gdpr\Model\HideCustomerData;

class AbstractHider
{
    /**
     * @var GridAnonymizationInterface[]
     */
    protected array $grids = [];

    public function __construct(protected \MageSuite\Gdpr\Helper\CustomerDataVisibility $customerDataVisibilityHelper) {}

    public function canSkipAnonymization(?string $result): bool
    {
        return empty($result)
            || $this->customerDataVisibilityHelper->canSeeCustomerData()
            || !$this->customerDataVisibilityHelper->shouldDataBeAnonymized();
    }

    protected function getAnonymizedString(?string $result): ?string
    {
        if ($this->canSkipAnonymization($result)) {
            return $result;
        }

        if ($this->isDate($result)) {
            return $this->getAnonymizedDate($result);
        }

        return $this->fillStringWithStars($result);
    }

    /**
     * Returns random date between 1918-01-01 and 2000-01-01
     */
    protected function getAnonymizedDate(string $result): string
    {
        if (!$this->customerDataVisibilityHelper->canSeeCustomerData() and $this->customerDataVisibilityHelper->shouldDataBeAnonymized()) {
            return date('Y-m-d', rand(-1640966400, 946713600));
        }

        return $result;
    }

    protected function fillStringWithStars(string $string): ?string
    {
        $length = mb_strlen($string);

        if ($length < 2) {
            return $string;
        }

        return mb_substr($string, 0, 1) . str_repeat('*', $length - 1);
    }

    protected function getGridToAnonymize(string $gridName): ?GridAnonymizationInterface
    {
        foreach ($this->grids as $grid) {
            if ($grid->canApplyToGrid($gridName)) {
                return $grid;
            }
        }

        return null;
    }

    protected function isDate(string $string): bool
    {
        return (bool)preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $string);
    }
}
