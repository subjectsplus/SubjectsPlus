import { useMutation, useQueryClient } from 'react-query';
import produce from 'immer';
import { createPluslet } from '@api/guide/PlusletAPI';
import { PlusletType } from '@shared/types/guide_types';

export const useCreatePluslet = (sectionUUID: string) => {
    const queryClient = useQueryClient();
    return useMutation(createPluslet, {
        onMutate: async (newPluslet: PlusletType) => {
            await queryClient.cancelQueries(['pluslets', sectionUUID]);
            const previousPlusletsData = queryClient.getQueryData<PlusletType[]>(['pluslets', sectionUUID]);
            
            if (previousPlusletsData) {
                const optimisticResult = produce<PlusletType[]>(previousPlusletsData, draftData => {
                    draftData.push(newPluslet);
                });
    
                queryClient.setQueryData(['pluslets', sectionUUID], optimisticResult);
            }

            return { previousPlusletsData };
        },
        onError: (error, newPluslet, context) => {
            // Perform rollback of pluslet mutation
            if (error) {
                console.error(error);
            }

            if (context) {
                queryClient.setQueryData(['pluslets', sectionUUID], context.previousPlusletsData);
            }
        },
        onSettled: () => {
            // Refetch the tab data
            queryClient.invalidateQueries(['pluslets', sectionUUID]);
        },
    });
}