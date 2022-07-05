import { useQueryClient, useMutation } from 'react-query';
import produce from 'immer';
import { updateTab } from '@api/guide/TabAPI';

export const useUpdateTab = (subjectId: number) => {
    const queryClient = useQueryClient();
    return useMutation(updateTab, {
        onMutate: async (updatedTab: Record<string, any>) => {
            await queryClient.cancelQueries(['tabs', subjectId]);
            const previousTabsData = queryClient.getQueryData<Record<string, any>>(['tabs', subjectId]);

            if (previousTabsData) {
                const optimisticResult = produce<Record<string, any>>(previousTabsData, draftData => {
                    draftData['hydra:member'][updatedTab.tabIndex] = updatedTab.optimisticResult;
                });

                queryClient.setQueryData(['tabs', subjectId], optimisticResult);
            }

            return { previousTabsData };
        },
        onError: (error, tabData, context) => {
            // Perform rollback of tab mutation
            if (error) {
                console.error(error);
            }

            if (context) {
                queryClient.setQueryData(['tabs', subjectId], context.previousTabsData);
            }
        },
        onSettled: () => {
            // Refetch the tab data
            queryClient.invalidateQueries(['tabs', subjectId]);
        },
    });
}