import { useMutation, useQueryClient } from 'react-query';
import produce from 'immer';
import { deleteSection } from '@api/guide/SectionAPI';
import { GuideSectionType } from '@shared/types/guide_types';

export const useDeleteSection = (tabUUID: string) => {
    const queryClient = useQueryClient();
    return useMutation(deleteSection, {
        onMutate: async deletedSection => {
            await queryClient.cancelQueries(['sections', tabUUID]);
            const previousSectionsData = queryClient.getQueryData<Record<string, any>>(['sections', tabUUID]);

            if (previousSectionsData) {
                const optimisticResult = produce<Record<string, any>>(previousSectionsData, draftData => {
                    draftData['hydra:member'] = draftData['hydra:member'].filter((section: GuideSectionType) => section.id !== deletedSection.sectionUUID);
                    draftData['hydra:member'].forEach((section: GuideSectionType, index: number) => section.sectionIndex = index);
                    draftData['hydra:totalItems'] = draftData['hydra:member'].length;
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