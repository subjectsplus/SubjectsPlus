export type MediaType = {
    mediaId: number,
    fileName: string,
    largeFileName?: string,
    mediumFileName?: string,
    smallFileName?: string,
    fileSize?: number,
    mimeType: string,
    createdAt?: string,
    updatedAt?: string,
    deletedAt?: string,
    staff?: string,
    title?: string,
    caption?: string,
    altText?: string,
    directory: string,
    largeImageFileWidth?: number,
    largeImageFileHeight?: number,
    mediumImageFileWidth?: number,
    mediumImageFileHeight?: number,
    smallImageFileWidth?: number,
    smallImageFileHeight?: number
}

export type ImageSizeType = 'small'|'medium'|'large';

export type ImageFileDimensions = {
    width: number|undefined,
    height: number|undefined
}