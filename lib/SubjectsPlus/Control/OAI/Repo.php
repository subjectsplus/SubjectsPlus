<?php
namespace SubjectsPlus\Control\OAI;

use DOMDocument;
use SubjectsPlus\Control\BaseUrl;
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
    private $publisher;
    private $language;
    private $identifierUrl;
    private $xslt;


    public function __construct(XSLTProcessor $xslt, array $setup)
    {
        $this->xslt = $xslt;
        $this->repositoryName = $setup['repositoryName'];
        $this->baseUrl = $setup['baseUrl'];
        $this->adminEmail = $setup['adminEmail'];
        $this->publisher = $setup['publisher'];
        $this->language = $setup['language'];
        $this->identifierUrl = $setup['identifierUrl'];
    }

    public function processRequest(Request $request)
    {
        $xsl = new DOMDocument();
        $xsl->load('./xsl/' . $request->verb . '.xsl');
        $this->xslt->importStylesheet($xsl);
        $this->setupBasicParams($this->xslt);
        return $this->xslt->transformToXml($xsl);
    }

    public function getRecords() {
        $recordList = new RecordList(new Querier());
        $xml = new DOMDocument();
        $xml->loadXML('<ListRecords xmlns="http://www.openarchives.org/OAI/2.0/"></ListRecords>');

        foreach ($recordList->getRecords() as $record) {
            $xsl = new DOMDocument();
            $xsl->load('./xsl/singleRecord.xsl');
            $this->xslt->importStylesheet($xsl);

            $this->setupBasicParams($this->xslt);
            $this->setupDcParams($this->xslt, $record);

            $f = $xml->createDocumentFragment();
            $f->appendXML($this->xslt->transformToXml($xsl));
            $xml->documentElement->appendChild($f);

        }

        $xml->saveXML();
        $xml->save('../assets/cache/recordlist.xml');

        return $xml;
    }

    public function getRecord(Request $request)
    {
        $record = new Record(new Querier());
        $record->getRecord($request->identifier);
        $xsl = new DOMDocument();
        $xsl->load('./xsl/GetRecord.xsl');
        $this->xslt->importStylesheet($xsl);

        $this->setupBasicParams($this->xslt);
        $this->setupDcParams($this->xslt, $record);
        return $this->xslt->transformToXml($xsl);
    }

    public function allRecordsXml() {
      return $this->getRecords()->saveXML();
    }

    public function listRecords()
    {
        //write the xml file first
        $this->getRecords();

        $xsl = new DOMDocument();
        $xsl->load('./xsl/ListRecords.xsl');
        $this->xslt->importStylesheet($xsl);

        $this->setupBasicParams($this->xslt);
        return $this->xslt->transformToXml($xsl);
    }


    public function setupBasicParams(XSLTProcessor $xslt)
    {
        global $baseUrl;
        
        $xslt->setParameter('', 'responseDate', date('c'));
        $xslt->setParameter('', 'recordDate', date('Y-m-d'));
        $xslt->setParameter('', 'repositoryName', $this->repositoryName);
        $xslt->setParameter('', 'baseUrl', $this->baseUrl);
        $xslt->setParameter('', 'adminEmail', $this->adminEmail);
    }

    public function setupDcParams(XSLTProcessor $xslt, Record $record)
    {

        $xslt->setParameter('', 'creator', $record->getCreator());
        $xslt->setParameter('', 'title', $record->getTitle());
        $xslt->setParameter('', 'description', $record->getDescription());
        $xslt->setParameter('', 'date', $record->getDate());
        $xslt->setParameter('', 'format', $record->getFormat());
        $xslt->setParameter('', 'language', $record->getLanguage());
        $xslt->setParameter('', 'publisher', $record->getPublisher());
        $xslt->setParameter('', 'identifier', $record->getIdentifier());
        $xslt->setParameter('', 'url', $this->identifierUrl . $record->getIdentifier());
        $xslt->setParameter('', 'type', $record->getType());
    }
}