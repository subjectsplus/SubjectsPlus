<?php
namespace SubjectsPlus\Control\OAI;
use DOMDocument;
use XSLTProcessor;

/**
 * Created by PhpStorm.
 * User: jlittle
 * Date: 7/14/16
 * Time: 12:12 PM
 */
class Repo
{

    private $repositoryName;
    private $baseUrl;
    private $adminEmail;
    private $xslt;
    
    public function __construct(XSLTProcessor $xslt, array $setup)
    {
        $this->xslt = $xslt;
        $this->repositoryName = $setup['repositoryName'];
        $this->baseUrl = $setup['baseUrl'];
        $this->adminEmail = $setup['adminEmail'];
    }

    public function identify(DOMDocument $xsl)
    {
        $this->xslt->importStylesheet($xsl);
        $this->xslt->setParameter('','responseDate',date('c'));
        $this->xslt->setParameter('','repositoryName',$this->repositoryName);
        $this->xslt->setParameter('','baseUrl',$this->baseUrl);
        $this->xslt->setParameter('','adminEmail',$this->adminEmail);
        return  $this->xslt->transformToXml($xsl);
    }

    public function badVerb(DOMDocument $xsl)
    {
        $this->xslt->importStylesheet($xsl);
        $this->xslt->setParameter('','responseDate',date('c'));
        $this->xslt->setParameter('','baseUrl',$this->baseUrl);
        return  $this->xslt->transformToXml($xsl);
    }

    public function listMetadataFormats(DOMDocument $xsl)
    {
        $this->xslt->importStylesheet($xsl);
        $this->xslt->setParameter('','responseDate',date('c'));
        $this->xslt->setParameter('','baseUrl',$this->baseUrl);
        return  $this->xslt->transformToXml($xsl);
    }

}