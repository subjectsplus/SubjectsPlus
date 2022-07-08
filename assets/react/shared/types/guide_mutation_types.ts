export type UpdateTabMutationArgs = {
    tabUUID: string,
    tabIndex?: number,
    data: Record<string, any>,
    optimisticResult?: Record<string, any>
}

export type DeleteTabMutationArgs = {
    tabUUID: string
}

export type ReorderTabMutationArgs = {
    subjectId: number, 
    sourceTabIndex: number, 
    destinationTabIndex: number
}

export type UpdateSectionMutationArgs = {
    sectionUUID: string,
    sectionIndex?: number,
    data: Record<string, any>,
    optimisticResult?: Record<string, any>
}

export type ReorderSectionMutationArgs = {
    tabUUID: string,
    sourceSectionIndex: number,
    destinationSectionIndex: number
}

export type ConvertSectionLayoutMutationArgs = {
    sectionUUID: string,
    newLayout: string,
    sectionIndex?: number,
    tabUUID?: string
}

export type DeleteSectionMutationArgs = {
    sectionUUID: string
}

export type UpdatePlusletMutationArgs = {
    plusletUUID: string,
    data: Record<string, any>
}

export type ReorderPlusletMutationArgs = {
    sourceSection: string,
    sourceColumn: number,
    sourceIndex: number,
    destinationSection: string,
    destinationColumn: number,
    destinationIndex: number
}

export type DeletePlusletMutationArgs = {
    plusletUUID: string
}