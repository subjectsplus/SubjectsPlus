export type TitleType = {
    titleId: number,
    title: string,
    alternateTitle?: string,
    description?: string,
    internalNotes?: string,
    pre?: string,
    lastModifiedBy?: string,
    lastModified?: string,
    location?: LocationType[]
}

export type LocationType = {
    locationId: number,
    callNumber?: string,
    location: string,
    eresDisplay?: string,
    displayNote?: string,
    helpguide?: string,
    citationGuide?: string,
    ctags?: string,
    recordStatus?: string,
    trialStart?: string,
    trialEnd?: string,
    format?: FormatType
    title?: string,
}

export type FormatType = {
    formatId: number,
    format: string
}