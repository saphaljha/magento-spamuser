<?php
namespace Saphaljha\Spamuser\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    private const XML_PATH_SPAMUSER = 'spamuser/spamuser_general/';

    /**
     * Config Value
     *
     * @param string $field
     * @param int|null $storeId
     * @return string
     */
    public function getConfigValue(string $field, int $storeId = null): string
    {
        return $this->scopeConfig->getValue(
            $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * General Config
     *
     * @param string $code
     * @param int|null $storeId
     * @return string
     */
    public function getGeneralConfig(string $code, int $storeId = null): string
    {
        return $this->getConfigValue(self::XML_PATH_SPAMUSER . $code, $storeId);
    }

    /**
     * Check is valid string
     *
     * @param string $str
     * @return bool
     */
    public function isValidString(string $str): bool
    {
        $disallowFirstLastName = $this->getGeneralConfig('disallow_strings_first_last');
        if ($disallowFirstLastName != '') {
            $disallowFirstLastNameExplode = explode(',', $disallowFirstLastName);
            foreach ($disallowFirstLastNameExplode as $_disallowFirstLastNameExplode) {
                if (strstr($str, trim($_disallowFirstLastNameExplode))) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Check is valid string email
     *
     * @param string $str
     * @return bool
     */
    public function isValidStringEmail(string $str): bool
    {
        $disallowEmail = $this->getGeneralConfig('disallow_strings_email');
        if ($disallowEmail != '') {
            $disallowEmailExplode = explode(',', $disallowEmail);
            foreach ($disallowEmailExplode as $_disallowEmailExplode) {
                if (strpos($str, trim($_disallowEmailExplode)) !== false) {
                    return true;
                }
            }
        }
        return false;
    }
}
