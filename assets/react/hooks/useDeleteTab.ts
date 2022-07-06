import { useQueryClient, useMutation } from 'react-query';
import produce from 'immer';
import { GuideTabType } from '@shared/types/guide_types';
import { deleteTab } from '@api/guide/TabAPI';
import { DeleteTabMutationArgs } from '@shared/types/guide_mutation_types';

export const useDeleteTab = (subjectId: number) => {
    const queryClient = useQueryClient();
    return useMutation(deleteTab, {
        onMutate: async (deletedTab: DeleteTabMutationArgs) => {
            await queryClient.cancelQueries(['tabs', subjectId]);
            const previousTabsData = queryClient.getQueryData<Record<string, any>>(['tabs', subjectId]);
            
            if (previousTabsData) {
                const optimisticResult = produce<Record<string, any>>(previousTabsData, draftData => {
                    draftData['hydra:member'] = draftData['hydra:member'].filter((tab: GuideTabType) => tab.id !== deletedTab.tabUUID);
                    draftData['hydra:member'].forEach((tab: GuideTabType, index: number) => tab.tabIndex = index);
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