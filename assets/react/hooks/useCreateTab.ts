import { useQueryClient, useMutation } from 'react-query';
import produce from 'immer';
import { GuideTabType } from '@shared/types/guide_types';
import { createTab } from '@api/guide/TabAPI';

export const useCreateTab = (subjectId: number) => {
    const queryClient = useQueryClient();
    return useMutation(createTab, {
        onMutate: async (newTab: GuideTabType) => {
            await queryClient.cancelQueries(['tabs', subjectId]);
            const previousTabsData = queryClient.getQueryData<Record<string, any>>(['tabs', subjectId]);

            if (previousTabsData) {
                const optimisticResult = produce<Record<string, any>>(previousTabsData, draftData => {
                    (draftData['hydra:member'] as GuideTabType[]).push(newTab);
                });

                queryClient.setQueryData(['tabs', subjectId], optimisticResult);
            }

            return { previousTabsData };
        },
        onError: (error, newTab, context) => {
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