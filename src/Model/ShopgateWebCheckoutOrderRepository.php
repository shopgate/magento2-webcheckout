<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Model;

use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;
use Magento\Payment\Api\Data\PaymentAdditionalInfoInterfaceFactory;
use Magento\Sales\Api\Data\OrderExtensionInterface;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterfaceFactory as SearchResultFactory;
use Magento\Sales\Model\Order\ShippingAssignmentBuilder;
use Magento\Tax\Api\OrderTaxManagementInterface;
use Shopgate\WebCheckout\Api\ShopgateWebCheckoutOrderRepositoryInterface;
use Shopgate\WebCheckout\Model\ResourceModel\ShopgateWebCheckoutOrder as WebCheckoutOrder;

class ShopgateWebCheckoutOrderRepository implements ShopgateWebCheckoutOrderRepositoryInterface
{
    /**
     * @param CollectionProcessorInterface          $collectionProcessor
     * @param SearchResultFactory                   $searchResultFactory
     * @param JoinProcessorInterface                $extensionAttributesJoinProcessor
     * @param OrderExtensionFactory                 $orderExtensionFactory
     * @param ShippingAssignmentBuilder             $shippingAssignmentBuilder
     * @param OrderTaxManagementInterface           $orderTaxManagement
     * @param PaymentAdditionalInfoInterfaceFactory $paymentAdditionalInfoFactory
     * @param JsonSerializer                        $serializer
     */
    public function __construct(
        private readonly CollectionProcessorInterface $collectionProcessor,
        private readonly SearchResultFactory $searchResultFactory,
        private readonly JoinProcessorInterface $extensionAttributesJoinProcessor,
        private readonly OrderExtensionFactory $orderExtensionFactory,
        private readonly ShippingAssignmentBuilder $shippingAssignmentBuilder,
        private readonly OrderTaxManagementInterface $orderTaxManagement,
        private readonly PaymentAdditionalInfoInterfaceFactory $paymentAdditionalInfoFactory,
        private readonly JsonSerializer $serializer
    ) {
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return OrderSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): OrderSearchResultInterface
    {
        $searchResult = $this->searchResultFactory->create();
        $select = $searchResult->getSelect();
        $select->joinInner(
            ['sgo' => $searchResult->getTable(WebCheckoutOrder::TABLE_NAME)],
            'main_table.entity_id = sgo.order_id',
            ['user_agent' => 'user_agent']
        );

        $this->extensionAttributesJoinProcessor->process($searchResult);
        $this->collectionProcessor->process($searchCriteria, $searchResult);
        $searchResult->setSearchCriteria($searchCriteria);

        foreach ($searchResult->getItems() as $order) {
            $this->setShippingAssignments($order);
            $this->setOrderTaxDetails($order);
            $this->setPaymentAdditionalInfo($order);
        }

        return $searchResult;
    }

    private function setShippingAssignments(OrderInterface $order): void
    {
        /** @var OrderExtensionInterface $extensionAttributes */
        $extensionAttributes = $order->getExtensionAttributes();

        if ($extensionAttributes === null) {
            $extensionAttributes = $this->orderExtensionFactory->create();
        } elseif ($extensionAttributes->getShippingAssignments() !== null) {
            return;
        }

        $this->shippingAssignmentBuilder->setOrderId($order->getEntityId());
        $extensionAttributes->setShippingAssignments($this->shippingAssignmentBuilder->create());
        $order->setExtensionAttributes($extensionAttributes);
    }

    private function setOrderTaxDetails(OrderInterface $order): void
    {
        $extensionAttributes = $order->getExtensionAttributes();
        if ($extensionAttributes === null) {
            $extensionAttributes = $this->orderExtensionFactory->create();
        }
        $orderTaxDetails = $this->orderTaxManagement->getOrderTaxDetails($order->getEntityId());
        $appliedTaxes = $orderTaxDetails->getAppliedTaxes();

        $extensionAttributes->setAppliedTaxes($appliedTaxes);
        $extensionAttributes->setConvertingFromQuote(false);

        $items = $orderTaxDetails->getItems();
        $extensionAttributes->setItemAppliedTaxes($items);

        $order->setExtensionAttributes($extensionAttributes);
    }

    private function setPaymentAdditionalInfo(OrderInterface $order): void
    {
        $extensionAttributes = $order->getExtensionAttributes();
        if ($extensionAttributes === null) {
            $extensionAttributes = $this->orderExtensionFactory->create();
        }

        $paymentAdditionalInformation = [];
        $payment = $order->getPayment();

        if ($payment) {
            $paymentAdditionalInformation = $payment->getAdditionalInformation();
        }

        $objects = [];
        foreach ($paymentAdditionalInformation as $key => $value) {
            $additionalInformationObject = $this->paymentAdditionalInfoFactory->create();
            $additionalInformationObject->setKey($key);

            if (!is_string($value)) {
                $value = $this->serializer->serialize($value);
            }
            $additionalInformationObject->setValue($value);

            $objects[] = $additionalInformationObject;
        }
        $extensionAttributes->setPaymentAdditionalInfo($objects);
        $order->setExtensionAttributes($extensionAttributes);
    }
}
