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
namespace WeArePlanet\Payment\Model\Webhook\Listener\TransactionCompletion;

use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Invoice;

/**
 * Webhook listener command to handle failed transaction completions.
 */
class FailedCommand extends AbstractCommand
{

    /**
     *
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     *
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     *
     * @param \WeArePlanet\Sdk\Model\TransactionCompletion $entity
     * @param Order $order
     */
    public function execute($entity, Order $order)
    {
        $transaction = $entity->getLineItemVersion()->getTransaction();
        $invoice = $this->getInvoiceForTransaction($transaction, $order);
        if ($invoice instanceof Invoice && $invoice->getWeareplanetCapturePending() &&
            $invoice->getState() == Invoice::STATE_OPEN) {
            $invoice->setWeareplanetCapturePending(false);

            /** @var \Magento\Sales\Model\Order\Payment $payment */
            $payment = $order->getPayment();
            $authTransaction = $payment->getAuthorizationTransaction();
            if($authTransaction) {
                $authTransaction->setIsClosed(false);
    
                $order->addRelatedObject($invoice);
                $order->addRelatedObject($authTransaction);
                $this->orderRepository->save($order);
            }
            else {
                throw new \Magento\Framework\Exception\LocalizedException(
                \__('Failing authorization in store failed: %1.',
                    \__('The associated authorization transaction for the payment could not be found.')));
            
            }
        }
    }
}