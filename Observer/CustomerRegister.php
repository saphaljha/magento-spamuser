<?php
namespace Saphaljha\Spamuser\Observer;

use Exception;
use Magento\Framework\App\ResponseFactory;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Saphaljha\Spamuser\Helper\Data;

class CustomerRegister implements ObserverInterface
{
    /**
     * @var LoggerInterface|null
     */
    protected ?LoggerInterface $logger = null;

    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $storeManager;

    /**
     * @var Data
     */
    protected Data $dataHelper;

    /**
     * @var ManagerInterface
     */
    protected ManagerInterface $messageManager;

    /**
     * @var ResponseFactory
     */
    private ResponseFactory $responseFactory;

    /**
     * @param Data $dataHelper
     * @param LoggerInterface $logger
     * @param StoreManagerInterface $storeManager
     * @param ManagerInterface $messageManager
     * @param ResponseFactory $responseFactory
     */
    public function __construct(
        Data $dataHelper,
        LoggerInterface $logger,
        StoreManagerInterface $storeManager,
        ManagerInterface $messageManager,
        ResponseFactory $responseFactory
    ) {
        $this->responseFactory = $responseFactory;
        $this->messageManager = $messageManager;
        $this->dataHelper = $dataHelper;
        $this->logger = $logger;
        $this->storeManager = $storeManager;
    }

    /**
     * Main function to be executed
     *
     * @param Observer $observer
     * @throws NoSuchEntityException
     */
    public function execute(Observer $observer): void
    {
        try {
            $customer = $observer->getCustomer();
            $firstName = $customer->getFirstname();
            $lastname = $customer->getLastname();
            $email = $customer->getEmail();

            if ($this->dataHelper->isValidString($firstName) ||
                $this->dataHelper->isValidString($lastname) ||
                $this->dataHelper->isValidStringEmail($email)
            ) {
                $currentUrl = $this->storeManager->getStore()->getBaseUrl() . 'customer/account/login/';

                $this->messageManager->addErrorMessage(__('Invalid customer data.'));
                $this->responseFactory->create()->setRedirect($currentUrl)->sendResponse();
            }
        } catch (Exception $e) {
            $this->logger->debug("customer_save_before observer failed: " . $e->getMessage());
        }
    }
}
