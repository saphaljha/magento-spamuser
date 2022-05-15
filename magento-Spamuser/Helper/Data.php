<?php

namespace Saphaljha\Spamuser\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    const XML_PATH_SPAMUSER = 'spamuser/spamuser_general/';

    /**
     * Config Value
     *
     * @param string $field
     * @return string
     */
    public function getConfigValue($field, $storeId = null)
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
     * @return string
     */
    public function getGeneralConfig($code, $storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_SPAMUSER . $code, $storeId);
    }

    /**
     * Check is valid string
     *
     * @param string $str
     * @return bool
     */
    public function isValidString($str)
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
    public function isValidStringEmail($str)
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
