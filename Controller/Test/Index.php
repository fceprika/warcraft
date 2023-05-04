<?php
namespace Blizzard\Warcraft\Controller\Test;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;
use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Customer\Model\Session;
use Blizzard\Warcraft\Model\WarcraftFactory;
use Blizzard\Warcraft\Model\ResourceModel\Warcraft as WarcraftResource;

class Index implements HttpGetActionInterface, HttpPostActionInterface
{
    /**
     * @var Context
     */
    protected Context $context;

    /**
     * @var PageFactory
     */
    protected PageFactory $resultPageFactory;

    /**
     * @var JsonFactory
     */
    protected JsonFactory $resultJsonFactory;

    /**
     * @var WarcraftFactory
     */
    protected $warcraftFactory;

    /**
     * @var WarcraftResource
     */
    protected $warcraftResource;

    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param JsonFactory $resultJsonFactory
     * @param WarcraftFactory $warcraftFactory
     * @param WarcraftResource $warcraftResource
     * @param Session $customerSession
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        JsonFactory $resultJsonFactory,
        WarcraftFactory $warcraftFactory,
        WarcraftResource $warcraftResource,
        Session $customerSession
    ) {
        $this->context = $context;
        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->warcraftFactory = $warcraftFactory;
        $this->warcraftResource = $warcraftResource;
        $this->customerSession = $customerSession;
    }

    /**
     * Dispatch request
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $result = $this->resultJsonFactory->create();
        if ($this->customerSession->isLoggedIn()) {

            $customerId = $this->customerSession->getCustomer()->getId();

            // Load the Warcraft model by customer ID
            $customer_char = $this->warcraftFactory->create();
            $warcraftResource = $this->warcraftResource;
            $this->warcraftResource->load($customer_char, $customerId, 'customer_id');

            $data = [
                'customer_id' => $customerId,
                'level' => 2,
                'experience' => 400,
                'promotion' => '-10%',
                'rank' => 'Palouf'
            ];

            if (!$customer_char->getId()) {
                // Insert a new row if the row with the same customer ID doesn't exist
                $connection = $warcraftResource->getConnection();
                $connection->insert($warcraftResource->getMainTable(), $data);
                $result->setData(['Message' => 'Data inserted']);
            } else {
                // Update the existing row if the row with the same customer ID exists
                $customer_char->setData($data);
                $warcraftResource->save($customer_char);
                $result->setData(['Message' => 'Data updated']);
            }

        } else {
            $result->setData(['Message' => 'Not logged in']);
        }
        return $result;
    }
}
