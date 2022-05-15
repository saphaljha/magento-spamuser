<?php
namespace Saphaljha\Spamuser\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class CustomerRegister implements ObserverInterface
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger = null;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Saphaljha\Spamuser\Helper\Data
     */
    protected $_dataHelper;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $_messageManager;

    /**
     * @var \Magento\Framework\App\ResponseFactory
     */
    private $_responseFactory;

    /**
     * @param \Saphaljha\Spamuser\Helper\Data $dataHelper
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Magento\Framework\App\ResponseFactory $responseFactory
     */
    public function __construct(
        \Saphaljha\Spamuser\Helper\Data $dataHelper,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\App\ResponseFactory $responseFactory
    ) {
        $this->_responseFactory = $responseFactory;
        $this->_messageManager = $messageManager;
        $this->_dataHelper = $dataHelper;
        $this->_logger = $logger;
        $this->_storeManager = $storeManager;
    }

    /**
     * @param Observer $observer
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute(Observer $observer)
    {
        try {
            $customer = $observer->getCustomer();
            $firstName = $customer->getFirstname();
            $lastname = $customer->getLastname();
            $email = $customer->getEmail();

            if ($this->_dataHelper->isValidString($firstName) || $this->_dataHelper->isValidString($lastname) || $this->_dataHelper->isValidStringEmail($email)) {
                $currentUrl = $this->_storeManager->getStore()->getBaseUrl() . 'customer/account/login/';

                $this->_messageManager->addError(__('Invalid customer data.'));
                $this->_responseFactory->create()->setRedirect($currentUrl)->sendResponse();
                exit();
            }
        } catch (Exception $e) {
            $this->_logger->addDebug("customer_save_before observer failed: " . $e->getMessage());
        }
    }
}
