
export const validateISBN = (candidate: string) => {
    const regex = new RegExp('(ISBN[-]*(1[03])*[ ]*(: ){0,1})*(([0-9Xx][- ]*){13}|([0-9Xx][- ]*){10})');
    return regex.test(candidate);
}