import { useQueryClient, useMutation } from 'react-query';
import produce from 'immer';
import { GuideTabType } from '@shared/types/guide_types';
import { deleteTab } from '@api/guide/TabAPI';

export const useDeleteTab = (subjectId: number) => {
    const queryClient = useQueryClient();
    return useMutation(deleteTab, {
        onMutate: async deletedTab => {
            await queryClient.cancelQueries(['tabs', subjectId]);
            const previousTabsData = queryClient.getQueryData<GuideTabType[]>(['tabs', subjectId]);
            
            if (previousTabsData) {
                const optimisticResult = produce<GuideTabType[]>(previousTabsData, draftData => {
                    const index = draftData.findIndex(tab => tab.id === deletedTab.tabUUID);
                    if (index !== -1) {
                        draftData.splice(index, 1);
                        draftData.forEach((tab, index) => tab.tabIndex = index);
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