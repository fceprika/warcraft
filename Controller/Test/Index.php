<?php
namespace Blizzard\Warcraft\Controller\Test;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;
use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;
use Blizzard\Warcraft\Model\WarcraftFactory;
use Magento\Framework\Message\ManagerInterface;

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

    protected $messageManager;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param JsonFactory $resultJsonFactory
     * @param WarcraftFactory $warcraftFactory
     * @param ManagerInterface $messageManager,
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        JsonFactory $resultJsonFactory,
        WarcraftFactory $warcraftFactory,
        ManagerInterface $messageManager
    ) {
        $this->context = $context;
        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->warcraftFactory = $warcraftFactory;
        $this->messageManager = $messageManager;
    }

    /**
     * Dispatch request
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $result = $this->resultJsonFactory->create();
        $customer_char = $this->warcraftFactory->create();
        $data = [
            'customer_id' => 2,
            'level' => 2,
            'experience' => 300,
            'promotion' => '-10%',
            'rank' => 2
        ];
        $customer_char->setData($data);
        // Save the data to the database
        try {
            $customer_char->save();
            $this->messageManager->addSuccessMessage(__('Data saved successfully.'));
            $result->setData(['Cities' => ['stormwind']]);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('An error occurred while saving the data.'));
            $result->setData(['Cities' => ['orgrimmar']]);
        }
        return $result;
    }
}
