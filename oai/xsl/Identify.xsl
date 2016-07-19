<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:xs="http://www.w3.org/2001/XMLSchema"
                exclude-result-prefixes="xs"
                version="1.0">
    <xsl:param name="repositoryName"></xsl:param>
    <xsl:param name="responseDate"></xsl:param>
    <xsl:param name="baseUrl"></xsl:param>
    <xsl:param name="adminEmail"></xsl:param>

    <xsl:template match="/">
        <xsl:call-template name="Identify"></xsl:call-template>
    </xsl:template>

    <xsl:template name="Identify">

        <OAI-PMH xmlns="http://www.openarchives.org/OAI/2.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                 xsi:schemaLocation="http://www.openarchives.org/OAI/2.0/ http://www.openarchives.org/OAI/2.0/OAI-PMH.xsd">
            <responseDate>
                <xsl:value-of select="$responseDate"/>
            </responseDate>
            <request verb="Identify">
                <xsl:value-of select="$baseUrl"/>
            </request>
            <Identify>
                <repositoryName>
                    <xsl:value-of select="$repositoryName"></xsl:value-of>
                </repositoryName>
                <baseURL>
                    <xsl:value-of select="$baseUrl"></xsl:value-of>
                </baseURL>
                <protocolVersion>2.0</protocolVersion>
                <adminEmail>
                    <xsl:value-of select="$adminEmail"></xsl:value-of>
                </adminEmail>
                <earliestDatestamp>2000-01-01</earliestDatestamp>
                <deletedRecord>no</deletedRecord>
                <granularity>YYYY-MM-DDThh:mm:ssZ</granularity>
            </Identify>
        </OAI-PMH>
    </xsl:template>
</xsl:stylesheet>