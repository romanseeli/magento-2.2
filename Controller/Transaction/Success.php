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
namespace WeArePlanet\Payment\Controller\Transaction;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Checkout\Model\Session\SuccessValidator;
use Magento\Framework\DataObject;
use Magento\Framework\App\Action\Context;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use WeArePlanet\Payment\Model\Service\Order\TransactionService;
use WeArePlanet\Sdk\Model\TransactionState;

/**
 * Frontend controller action to handle successful payments.
 */
class Success extends \WeArePlanet\Payment\Controller\Transaction
{
    /**
     *
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     *
     * @var SuccessValidator
     */
    private $successValidator;

    /**
     *
     * @var TransactionService
     */
    private $transactionService;

    /**
     *
     * @param Context $context
     * @param OrderRepositoryInterface $orderRepository
     * @param CheckoutSession $checkoutSession
     * @param SuccessValidator $successValidator
     * @param TransactionService $transactionService
     */
    public function __construct(Context $context, OrderRepositoryInterface $orderRepository,
        CheckoutSession $checkoutSession, SuccessValidator $successValidator, TransactionService $transactionService)
    {
        parent::__construct($context, $orderRepository);
        $this->checkoutSession = $checkoutSession;
        $this->successValidator = $successValidator;
        $this->transactionService = $transactionService;
    }

    public function execute()
    {
        $order = $this->getOrder();
        if (! $this->successValidator->isValid()) {
            $this->messageManager->addErrorMessage(
                \__(
                    'There seems to have been a problem with your order. ' .
                    'However, the payment was successful. Please contact us.'));
            return $this->_redirect('checkout/cart');
        }

        $this->checkoutSession->setLastOrderId($order->getId())
            ->setLastRealOrderId($order->getIncrementId())
            ->setLastOrderStatus($order->getStatus());

        return $this->_redirect($this->getSuccessRedirectionPath($order));
    }

    /**
     * Gets the path to redirect the customer to.
     *
     * @param Order $order
     * @return string
     */
    private function getSuccessRedirectionPath(Order $order)
    {
        $response = new DataObject();
        $response->setPath('checkout/onepage/success');
        $this->_eventManager->dispatch('weareplanet_success_redirection_path',
            [
                'order' => $order,
                'response' => $response
            ]);
        return $response->getPath();
    }
}