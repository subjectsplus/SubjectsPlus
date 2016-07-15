<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xs="http://www.w3.org/2001/XMLSchema"
    exclude-result-prefixes="xs"
    version="1.0">

    <xsl:param name="responseDate"></xsl:param>
    <xsl:param name="baseUrl"></xsl:param>


    <xsl:template match="/">
        <xsl:call-template name="badVerb"></xsl:call-template>
    </xsl:template>
    
    <xsl:template name="badVerb">
        <OAI-PMH xmlns="http://www.openarchives.org/OAI/2.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.openarchives.org/OAI/2.0/ http://www.openarchives.org/OAI/2.0/OAI-PMH.xsd">
            <responseDate><xsl:value-of select="$responseDate"/></responseDate>
            <request verb="Identify"><xsl:value-of select="$baseUrl"/></request>
            <error code="badVerb">
                The value of the verb argument is not a legal OAI-PMH verb, the verb argument is missing, or the verb argument is repeated. "missing verb value"
            </error>
        </OAI-PMH>
    </xsl:template>
</xsl:stylesheet>