<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:xs="http://www.w3.org/2001/XMLSchema"
                exclude-result-prefixes="xs"
                version="1.0">


    <xsl:output indent="yes" omit-xml-declaration="no"></xsl:output>
    <xsl:param name="responseDate"></xsl:param>
    <xsl:param name="baseUrl"></xsl:param>
    <xsl:param name="records"></xsl:param>
    <xsl:param name="url"></xsl:param>
    <xsl:variable name="recordlist" select="document('../../assets/cache/recordlist.xml')"/>
    <xsl:template match="/">
        <xsl:call-template name="ListRecords"></xsl:call-template>
    </xsl:template>

    <xsl:template name="ListRecords"><OAI-PMH xmlns="http://www.openarchives.org/OAI/2.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.openarchives.org/OAI/2.0/ http://www.openarchives.org/OAI/2.0/OAI-PMH.xsd">
            <responseDate><xsl:value-of select="$responseDate"/></responseDate>
            <request verb="ListRecords"><xsl:value-of select="$baseUrl"/></request>
            <xsl:copy-of select="$recordlist"></xsl:copy-of>
        </OAI-PMH>
    </xsl:template>
</xsl:stylesheet>