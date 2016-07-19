<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:xs="http://www.w3.org/2001/XMLSchema"
                exclude-result-prefixes="xs"
                version="1.0">

    <xsl:param name="responseDate"></xsl:param>
    <xsl:param name="baseUrl"></xsl:param>


    <xsl:template match="/">
        <xsl:call-template name="ListMetadataFormats"></xsl:call-template>
    </xsl:template>

    <xsl:template name="ListMetadataFormats">
        <OAI-PMH xmlns="http://www.openarchives.org/OAI/2.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.openarchives.org/OAI/2.0/ http://www.openarchives.org/OAI/2.0/OAI-PMH.xsd">
            <responseDate><xsl:value-of select="$responseDate"/></responseDate>
            <request verb="ListMetadataFormats"><xsl:value-of select="$baseUrl"/></request>
            <ListMetadataFormats>
                <metadataFormat>
                    <metadataPrefix>oai_dc</metadataPrefix>
                    <schema>http://www.openarchives.org/OAI/2.0/oai_dc.xsd</schema>
                    <metadataNamespace>http://www.openarchives.org/OAI/2.0/oai_dc/</metadataNamespace>
                </metadataFormat>
            </ListMetadataFormats>
        </OAI-PMH>
    </xsl:template>
</xsl:stylesheet>