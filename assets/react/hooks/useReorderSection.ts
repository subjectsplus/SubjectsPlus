import { useMutation, useQueryClient } from 'react-query';
import produce from 'immer';
import { reorderSection } from '@api/guide/SectionAPI';
import { GuideSectionType } from '@shared/types/guide_types';
import { ReorderSectionMutationArgs } from '@shared/types/guide_mutation_types';

export const useReorderSection = (tabUUID: string) => {
    const queryClient = useQueryClient();
    return useMutation(reorderSection, {
        onMutate: async (sectionData: ReorderSectionMutationArgs) => {
            await queryClient.cancelQueries(['sections', tabUUID]);
            const previousSectionsData = queryClient.getQueryData<Record<string, any>>(['sections', tabUUID]);

            if (previousSectionsData) {
                const optimisticResult = produce<Record<string, any>>(previousSectionsData, draftData => {
                    const [reorderedSection]: [GuideSectionType] = draftData['hydra:member'].splice(sectionData.sourceSectionIndex, 1);
                    draftData['hydra:member'].splice(sectionData.destinationSectionIndex, 0, reorderedSection);
                    draftData['hydra:member'].forEach((section: GuideSectionType, index: number) => section.sectionIndex = index);
                });
    
                queryClient.setQueryData(['sections', tabUUID], optimisticResult);
            }

            return { previousSectionsData };
        },
        onError: (error, sectionsData, context) => {
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