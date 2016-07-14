<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:param name="baseUrl"/>
    <xsl:param name="responseDate"/>

    <xsl:template match="/">
        <xsl:call-template name="ListSets"></xsl:call-template>
    </xsl:template>

    <xsl:template name="ListSets">
        <OAI-PMH xmlns="http://www.openarchives.org/OAI/2.0/"
                 xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                 xsi:schemaLocation="http://www.openarchives.org/OAI/2.0/
         http://www.openarchives.org/OAI/2.0/OAI-PMH.xsd">
            <responseDate><xsl:value-of select="$responseDate"></xsl:value-of></responseDate>
            <request verb="ListSets"><xsl:value-of select="$baseUrl"></xsl:value-of></request>
            <error code="noSetHierarchy">This repository does not
                support sets</error>
        </OAI-PMH>
    </xsl:template>

</xsl:stylesheet>