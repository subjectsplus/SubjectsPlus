import { useQueryClient, useMutation } from 'react-query';
import produce from 'immer';
import { GuideTabType } from '@shared/types/guide_types';
import { reorderTab } from '@api/guide/TabAPI';
import { ReorderTabMutationArgs } from '@shared/types/guide_mutation_types';

export function useReorderTab(subjectId: number) {
    if (subjectId === undefined) throw new Error('"subjectId" field is required to call useReorderTab.');

    const queryClient = useQueryClient();
    return useMutation(reorderTab, {
        onMutate: async (tabData: ReorderTabMutationArgs) => {
            await queryClient.cancelQueries(['tabs', subjectId]);
            const previousTabsData = queryClient.getQueryData<Record<string, any>>(['tabs', subjectId]);

            if (previousTabsData) {
                const optimisticResult = produce<Record<string, any>>(previousTabsData, draftData => {
                    const [reorderedTab] = draftData['hydra:member'].splice(tabData.sourceTabIndex, 1);
                    draftData['hydra:member'].splice(tabData.destinationTabIndex, 0, reorderedTab);
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