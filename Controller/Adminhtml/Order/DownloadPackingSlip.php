<?php
/**
 * WeArePlanet Magento 2
 *
 * This Magento 2 extension enables to process payments with WeArePlanet (https://www.weareplanet.com//).
 *
 * @package WeArePlanet_Payment
 * @author wallee AG (http://www.wallee.com/)
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache Software License (ASL 2.0)
 */
namespace WeArePlanet\Payment\Controller\Adminhtml\Order;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Controller\Result\ForwardFactory;
use WeArePlanet\Payment\Api\TransactionInfoRepositoryInterface;
use WeArePlanet\Payment\Model\ApiClient;
use WeArePlanet\Sdk\Service\TransactionService;

/**
 * Backend controller action to download a packing slip.
 */
class DownloadPackingSlip extends \WeArePlanet\Payment\Controller\Adminhtml\Order
{

    /**
     *
     * @var ForwardFactory
     */
    private $resultForwardFactory;

    /**
     *
     * @var FileFactory
     */
    private $fileFactory;

    /**
     *
     * @var TransactionInfoRepositoryInterface
     */
    private $transactionInfoRepository;

    /**
     *
     * @var ApiClient
     */
    private $apiClient;

    /**
     *
     * @param Context $context
     * @param ForwardFactory $resultForwardFactory
     * @param FileFactory $fileFactory
     * @param TransactionInfoRepositoryInterface $transactionInfoRepository
     * @param ApiClient $apiClient
     */
    public function __construct(Context $context, ForwardFactory $resultForwardFactory, FileFactory $fileFactory,
        TransactionInfoRepositoryInterface $transactionInfoRepository, ApiClient $apiClient)
    {
        parent::__construct($context);
        $this->resultForwardFactory = $resultForwardFactory;
        $this->fileFactory = $fileFactory;
        $this->transactionInfoRepository = $transactionInfoRepository;
        $this->apiClient = $apiClient;
    }

    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Magento_Sales::shipment';

    public function execute()
    {
        $orderId = $this->getRequest()->getParam('order_id');
        if ($orderId) {
            $transaction = $this->transactionInfoRepository->getByOrderId($orderId);
            $document = $this->apiClient->getService(TransactionService::class)->getPackingSlip(
                $transaction->getSpaceId(), $transaction->getTransactionId());
            return $this->fileFactory->create($document->getTitle() . '.pdf', \base64_decode($document->getData()),
                DirectoryList::VAR_DIR, 'application/pdf');
        } else {
            return $this->resultForwardFactory->create()->forward('noroute');
        }
    }
}