<?php

namespace VertexNet\FacebookFeed\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const GS_CONFIG_PATH = 'vertexnetfacebookfeed/settings/';

    public function getConfig($configNode)
    {
        return $this->scopeConfig->getValue(
            self::GS_CONFIG_PATH.$configNode,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}