import { useMutation, useQueryClient } from 'react-query';
import produce from 'immer';
import { updateSection } from '@api/guide/SectionAPI';
import { GuideSectionType } from '@shared/types/guide_types';

export const useUpdateSection = (tabUUID: string) => {
    const queryClient = useQueryClient();
    return useMutation(updateSection, {
        onMutate: async updatedSection => {
            await queryClient.cancelQueries(['sections', tabUUID]);
            const previousSectionsData = queryClient.getQueryData<GuideSectionType[]>(['sections', tabUUID]);

            if (previousSectionsData) {
                const optimisticResult = produce<GuideSectionType[]>(previousSectionsData, draftData => {
                    if (typeof updatedSection.sectionIndex !== 'undefined' && updatedSection.optimisticResult) {
                        draftData[updatedSection.sectionIndex] = updatedSection.optimisticResult;
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
            // Refetch the section data
            queryClient.invalidateQueries(['sections', tabUUID]);
        },
    });
}