import { useQueryClient, useMutation } from 'react-query';
import produce from 'immer';
import { updateTab } from '@api/guide/TabAPI';
import { GuideTabType } from '@shared/types/guide_types';

export const useUpdateTab = (subjectId: number) => {
    const queryClient = useQueryClient();
    return useMutation(updateTab, {
        onMutate: async updatedTab => {
            await queryClient.cancelQueries(['tabs', subjectId]);
            const previousTabsData = queryClient.getQueryData<GuideTabType[]>(['tabs', subjectId]);

            if (previousTabsData) {
                const optimisticResult = produce<GuideTabType[]>(previousTabsData, draftData => {
                    if (draftData && typeof updatedTab.tabIndex !== 'undefined' && updatedTab.optimisticResult) {
                        draftData[updatedTab.tabIndex] = updatedTab.optimisticResult;
                    }
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