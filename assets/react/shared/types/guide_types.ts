export type GuideType = {
    subjectId?: number,
    subject?: string,
    active?: number,
    shortform?: string,
    redirectUrl?: string,
    header?: string,
    description?: string,
    keywords?: string,
    type?: string,
    lastModified?: string,
    extra?: string,
    courseCode?: string,
    instructor?: string,
    staff?: string[]
};

export type GuideTabType = {
    id: string,
    tabId?: number,
    label: string,
    tabIndex: number,
    externalUrl?: string|null,
    visibility?: boolean,
    parent?: string,
    children?: string,
    extra?: string,
    subject: string,
};

export type GuideSectionType = {
    id: string,
    sectionId?: number,
    sectionIndex: number,
    layout: string,
    tab: string
};

export type PlusletType = {
    id: string,
    plusletId?: number,
    title: string,
    body: string,
    type: string,
    extra?: Record<string, any>|null,
    favoriteBox?: boolean,
    pcolumn: number,
    prow: number,
    section: string
}