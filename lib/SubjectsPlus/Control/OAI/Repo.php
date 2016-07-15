<?php
namespace SubjectsPlus\Control\OAI;
use DOMDocument;
use SubjectsPlus\Control\Querier;
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

    public function processRequest(Request $request) {
        $xsl = new DOMDocument();
        $xsl->load('./xsl/'. $request->verb.'.xsl');
        $this->xslt->importStylesheet($xsl);
        $this->xslt->setParameter('','responseDate',date('c'));
        $this->xslt->setParameter('','repositoryName',$this->repositoryName);
        $this->xslt->setParameter('','baseUrl',$this->baseUrl . $request->queryString);
        $this->xslt->setParameter('','adminEmail',$this->adminEmail);
        return  $this->xslt->transformToXml($xsl);
    }

    public function getRecord(Request $request) {
        $xsl = new DOMDocument();
        $xsl->load('./xsl/GetRecord.xsl');
        $this->xslt->importStylesheet($xsl);
        
        $record = new Record(new Querier());
        $record->getRecord($request->identifier);

        $this->xslt->setParameter('','creator',$record->getCreator());
        $this->xslt->setParameter('','title',$record->getTitle());

        
        $this->xslt->setParameter('','responseDate',date('c'));
        $this->xslt->setParameter('','repositoryName',$this->repositoryName);
        $this->xslt->setParameter('','baseUrl',$this->baseUrl . $request->queryString);
        $this->xslt->setParameter('','adminEmail',$this->adminEmail);
        return  $this->xslt->transformToXml($xsl);
    }

    public function getRecordList(Request $request) {

        $records = new RecordList(new Querier());

        return $records;
    }
}