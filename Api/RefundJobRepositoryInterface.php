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
namespace WeArePlanet\Payment\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use WeArePlanet\Payment\Api\Data\RefundJobInterface;

/**
 * Refund job CRUD interface.
 *
 * @api
 */
interface RefundJobRepositoryInterface
{

    /**
     * Create refund job
     *
     * @param RefundJobInterface $object
     * @return RefundJobInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(RefundJobInterface $object);

    /**
     * Get job about refund job by entity ID
     *
     * @param int $entityId
     * @return RefundJobInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($entityId);

    /**
     * Get job about refund job by order ID
     *
     * @param int $orderId
     * @return RefundJobInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByOrderId($orderId);

    /**
     * Get job about refund job by external ID
     *
     * @param string $externalId
     * @return RefundJobInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByExternalId($externalId);

    /**
     * Retrieve refund jobs matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return \WeArePlanet\Payment\Api\Data\RefundJobSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete refund job
     *
     * @param RefundJobInterface $object
     * @return bool Will returned True if deleted
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function delete(RefundJobInterface $object);

    /**
     * Delete refund job by identifier
     *
     * @param string $entityId
     * @return bool Will returned True if deleted
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function deleteByIdentifier($entityId);
}