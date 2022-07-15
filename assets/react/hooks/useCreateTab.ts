import { useQueryClient, useMutation } from 'react-query';
import produce from 'immer';
import { GuideTabType } from '@shared/types/guide_types';
import { createTab } from '@api/guide/TabAPI';

export const useCreateTab = (subjectId: number) => {
    const queryClient = useQueryClient();
    return useMutation(createTab, {
        onMutate: async (newTab: GuideTabType) => {
            await queryClient.cancelQueries(['tabs', subjectId]);
            const previousTabsData = queryClient.getQueryData<GuideTabType[]>(['tabs', subjectId]);

            if (previousTabsData) {
                const optimisticResult = produce<GuideTabType[]>(previousTabsData, draftData => {
                    draftData.push(newTab);
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