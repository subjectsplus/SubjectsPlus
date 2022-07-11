import { useMutation, useQueryClient } from 'react-query';
import produce from 'immer';
import { deletePluslet } from '@api/guide/PlusletAPI';
import { PlusletType } from '@shared/types/guide_types';

export const useDeletePluslet = (sectionUUID: string) => {
    const queryClient = useQueryClient();
    return useMutation(deletePluslet, {
        onMutate: async deletedPluslet => {
            await queryClient.cancelQueries(['pluslets', sectionUUID]);
            const previousPlusletsData = queryClient.getQueryData<PlusletType[]>(['pluslets', sectionUUID]);

            if (previousPlusletsData) {
                const optimisticResult = produce<PlusletType[]>(previousPlusletsData, draftData => {
                    const updates: Record<string, any> = {};
                    const plusletToRemove = draftData.find(pluslet => pluslet.id === deletedPluslet.plusletUUID);

                    if (plusletToRemove) {
                        const columnPluslets = draftData.filter(pluslet => pluslet.pcolumn === plusletToRemove.pcolumn)
                        .filter(pluslet => pluslet.id !== plusletToRemove.id);
        
                        columnPluslets.forEach((pluslet, index) => {
                            if (pluslet.prow !== index) {
                                updates[pluslet.id] = {
                                    prow: index
                                };
                            }
                        });
        
                        draftData = draftData.filter(pluslet => pluslet.id !== plusletToRemove.id);
                        draftData.forEach(pluslet => {
                            if (updates[pluslet.id]) {
                                pluslet.prow = updates[pluslet.id].prow;
                            }
                        });
                    }
                });
                
                queryClient.setQueryData(['pluslets', sectionUUID], optimisticResult);
            }
            
            return { previousPlusletsData };
        },
        onError: (error, plusletData, context) => {
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
        }
    });
}