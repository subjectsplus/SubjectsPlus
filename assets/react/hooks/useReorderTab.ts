import { useQueryClient, useMutation } from 'react-query';
import produce from 'immer';
import { GuideTabType } from '@shared/types/guide_types';
import { reorderTab } from '@api/guide/TabAPI';

export function useReorderTab(subjectId: number) {
    const queryClient = useQueryClient();
    return useMutation(reorderTab, {
        onMutate: async tabData => {
            await queryClient.cancelQueries(['tabs', subjectId]);
            const previousTabsData = queryClient.getQueryData<GuideTabType[]>(['tabs', subjectId]);

            if (previousTabsData) {
                const optimisticResult = produce<GuideTabType[]>(previousTabsData, draftData => {
                    const [reorderedTab] = draftData.splice(tabData.sourceTabIndex, 1);
                    draftData.splice(tabData.destinationTabIndex, 0, reorderedTab);
                    draftData.forEach((tab, index) => tab.tabIndex = index);
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