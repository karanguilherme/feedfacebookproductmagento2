<?php

namespace VertexNet\FacebookFeed\Model;

class Xmlfeed
{
    /**
     * General Helper
     *
     * @var \VertexNet\FacebookFeed\Helper\Data
     */
    private $_helper;

    /**
     * Product Helper
     *
     * @var \VertexNet\FacebookFeed\Helper\Products
     */
    private $_productFeedHelper;

    /**
     * Store Manager
     *
     * @var \VertexNet\FacebookFeed\Helper\Products
     */
    private $_storeManager;

    public function __construct(
        \VertexNet\FacebookFeed\Helper\Data $helper,
        \VertexNet\FacebookFeed\Helper\Products $productFeedHelper,
        \Magento\Store\Model\StoreManagerInterface $storeManager

    ) {
        $this->_helper = $helper;
        $this->_productFeedHelper = $productFeedHelper;
        $this->_storeManager = $storeManager;
    }

    public function getFeed()
    {
        $xml = $this->getXmlHeader();
        $xml .= $this->getProductsXml();
        $xml .= $this->getXmlFooter();

        return $xml;
    }

    public function getXmlHeader()
    {

        header("Content-Type: application/xml; charset=utf-8");

        $xml =  '<rss version="2.0" xmlns:g="http://base.facebook.com/ns/1.0">';
        $xml .= '<channel>';
        $xml .= '<title>'.$this->_helper->getConfig('facebook_default_title').'</title>';
        $xml .= '<link>'.$this->_helper->getConfig('facebook_default_url').'</link>';
        $xml .= '<description>'.$this->_helper->getConfig('facebook_default_description').'</description>';

        return $xml;

    }

    public function getXmlFooter()
    {
        return  '</channel></rss>';
    }

    public function getProductsXml()
    {
        $productCollection = $this->_productFeedHelper->getFilteredProducts();
        $xml = "";

        foreach ($productCollection as $product)
        {
            $xml .= "<item>".$this->buildProductXml($product)."</item>";
        }

        return $xml;
    }

    public function buildProductXml($product)
    {
      if ( empty($product->getInstallments() ) ){

      $qtdeP = 1;

      }else{

      $qtdeP = $product->getInstallments();

      }

     if ($product->getSpecialPrice() != 0.00){
      $valor = $product->getSpecialPrice() / $qtdeP ;
    }else{
      $valor = $product->getFinalPrice() / $qtdeP;
    }

        $_description = $this->fixDescription($product->getDescription());
        $xml = $this->createNode("id", $product->getId());
        $xml .= $this->createNode("title", $product->getName(), true);
        $xml .= $this->createNode("description", $_description, true);
        $xml .= $this->createNode("availability", 'in stock');
        $_condition = $product->getAttributeText('facebook_condiction');
        if (is_array($_condition))
            $xml .= $this->createNode("g:condition", $_condition[0]);
        else
            $xml .= $this->createNode("g:condition", $_condition);
        $xml .= $this->createNode('price', number_format($product->getFinalPrice(),2,'.','').' '.$this->_productFeedHelper->getCurrentCurrencySymbol());
        $xml .= $this->createNode("link", $product->getProductUrl());
        $xml .= $this->createNode("image_link", $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA, true).'catalog/product'.$product->getImage());
        $xml .= $this->createNode("brand", $product->getAttributeText('manufacturer'));


        return $xml;


    }

    public function fixDescription($data)
    {
        $description = $data;
        $encode = mb_detect_encoding($data);
        $description = mb_convert_encoding($description, 'UTF-8', $encode);

        return $description;
    }

    public function createNode($nodeName, $value, $cData = false)
    {
        if (empty($value) || empty ($nodeName))
        {
            return false;
        }

        $cDataStart = "";
        $cDataEnd = "";

        if ($cData === true)
        {
            $cDataStart = "<![CDATA[";
            $cDataEnd = "]]>";
        }

        $node = "<".$nodeName.">".$cDataStart.$value.$cDataEnd."</".$nodeName.">";

        return $node;
    }

}
