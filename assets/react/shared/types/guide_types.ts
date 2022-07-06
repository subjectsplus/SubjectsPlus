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
}

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
}