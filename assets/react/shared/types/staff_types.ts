import { MediaAttachmentType } from './media_types';

export type StaffType = {
    staffId: number,
    lname: string,
    fname: string,
    title: string,
    tel: string,
    email: string,
    socialMedia: string|null,
    staffPhoto: MediaAttachmentType|null
}