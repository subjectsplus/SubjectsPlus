<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:xs="http://www.w3.org/2001/XMLSchema"
                exclude-result-prefixes="xs"
                version="1.0">

    <xsl:param name="baseUrl"/>
    <xsl:param name="responseDate"/>

    <xsl:template match="/">
        <xsl:call-template name="badArgument"></xsl:call-template>
    </xsl:template>

    <xsl:template name="badArgument">
        <OAI-PMH xmlns="http://www.openarchives.org/OAI/2.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                 xsi:schemaLocation="http://www.openarchives.org/OAI/2.0/ http://www.openarchives.org/OAI/2.0/OAI-PMH.xsd">
            <responseDate><xsl:value-of select="$responseDate"></xsl:value-of></responseDate>
            <request><xsl:value-of select="$baseUrl"></xsl:value-of></request>
            <error code="badArgument">The request includes illegal arguments, repeated arguments, or is missing required
                arguments. "set"
            </error>
        </OAI-PMH>
    </xsl:template>
</xsl:stylesheet>