import { useMutation, useQueryClient } from 'react-query';
import produce from 'immer';
import { updatePluslet } from '@api/guide/PlusletAPI';
import { PlusletType } from '@shared/types/guide_types';
import { UpdatePlusletMutationArgs } from '@shared/types/guide_mutation_types';

export function useUpdatePluslet(sectionUUID: string) {
    const queryClient = useQueryClient();
    return useMutation(updatePluslet, {
        onMutate: async updatedPluslet => {
            await queryClient.cancelQueries(['pluslets', sectionUUID]);
            const previousPlusletsData = queryClient.getQueryData<Record<string, any>>(['pluslets', sectionUUID]);

            if (previousPlusletsData) {
                const optimisticResult = produce<Record<string, any>>(previousPlusletsData, draftData => {
                    const pluslets = (draftData['hydra:member'] as PlusletType[])
                    const index = pluslets.findIndex(pluslet => pluslet.id === updatedPluslet.plusletUUID);

                    Object.keys(updatedPluslet.data).map(key => {
                        draftData['hydra:member'][index][key] = updatedPluslet.data[key];
                    });
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