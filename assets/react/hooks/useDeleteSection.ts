import { useMutation, useQueryClient } from 'react-query';
import produce from 'immer';
import { deleteSection } from '@api/guide/SectionAPI';
import { GuideSectionType } from '@shared/types/guide_types';

export const useDeleteSection = (tabUUID: string) => {
    const queryClient = useQueryClient();
    return useMutation(deleteSection, {
        onMutate: async deletedSection => {
            await queryClient.cancelQueries(['sections', tabUUID]);
            const previousSectionsData = queryClient.getQueryData<GuideSectionType[]>(['sections', tabUUID]);

            if (previousSectionsData) {
                const optimisticResult = produce<GuideSectionType[]>(previousSectionsData, draftData => {
                    const index = draftData.findIndex(section => section.id === deletedSection.sectionUUID);
                    if (index !== -1) {
                        draftData.splice(index, 1);
                        draftData.forEach((section, index) => section.sectionIndex = index);
                    }
                });
                
                queryClient.setQueryData(['sections', tabUUID], optimisticResult);
            }
            
            return { previousSectionsData };
        },
        onError: (error, sectionData, context) => {
            // Perform rollback of section mutation
            if (error) {
                console.error(error);
            }

            if (context) {
                queryClient.setQueryData(['sections', tabUUID], context.previousSectionsData);
            }
        },
        onSettled: () => {
            // Refetch the tab data
            queryClient.invalidateQueries(['sections', tabUUID]);
        },
    });
}