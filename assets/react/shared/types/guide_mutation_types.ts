export type UpdateTabMutationArgs = {
    tabUUID: string,
    tabIndex?: number,
    data: Record<string, any>,
    optimisticResult?: Record<string, any>
}