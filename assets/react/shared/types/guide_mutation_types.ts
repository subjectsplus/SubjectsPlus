export type UpdateTabMutationArgs = {
    tabUUID: string,
    tabIndex?: number,
    data: Record<string, any>,
    optimisticResult?: Record<string, any>
}

export type DeleteTabMutationArgs = {
    tabUUID: string
}