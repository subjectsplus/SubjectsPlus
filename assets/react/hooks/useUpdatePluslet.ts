import { useMutation, useQueryClient } from 'react-query';
import produce from 'immer';
import { updatePluslet } from '@api/guide/PlusletAPI';
import { PlusletType } from '@shared/types/guide_types';

export function useUpdatePluslet(sectionUUID: string) {
    const queryClient = useQueryClient();
    return useMutation(updatePluslet, {
        onMutate: async updatedPluslet => {
            await queryClient.cancelQueries(['pluslets', sectionUUID]);
            const previousPlusletsData = queryClient.getQueryData<PlusletType[]>(['pluslets', sectionUUID]);

            if (previousPlusletsData) {
                const optimisticResult = produce<PlusletType[]>(previousPlusletsData, draftData => {
                    const index = draftData.findIndex(pluslet => pluslet.id === updatedPluslet.plusletUUID);
                    
                    if (index !== -1) {
                        draftData[index] = {
                            ...draftData[index],
                            ...updatedPluslet.data
                        }
                    }
                });
                
                queryClient.setQueryData(['pluslets', sectionUUID], optimisticResult);
            }

            return { previousPlusletsData };
        },
        onError: (error, plusletData, context) => {
            // Perform rollback of pluslets mutation
            if (error) {
                console.error(error);
            }

            if (context) {
                queryClient.setQueryData(['pluslets', sectionUUID], context.previousPlusletsData);
            }
        },
        onSettled: () => {
            // Refetch the pluslets data
            queryClient.invalidateQueries(['pluslets', sectionUUID]);
        },
    });
}