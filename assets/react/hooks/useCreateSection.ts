import { useMutation, useQueryClient } from 'react-query';
import produce from 'immer';
import { createSection } from '@api/guide/SectionAPI';
import { GuideSectionType } from '@shared/types/guide_types';

export const useCreateSection = (tabUUID: string) => {
    const queryClient = useQueryClient();
    return useMutation(createSection, {
        onMutate: async (newSection: GuideSectionType) => {
            await queryClient.cancelQueries(['sections', tabUUID]);
            const previousSectionsData = queryClient.getQueryData<GuideSectionType[]>(['sections', tabUUID]);

            if (previousSectionsData) {
                const optimisticResult = produce<GuideSectionType[]>(previousSectionsData, draftData => {
                    draftData.push(newSection);
                });
                
                queryClient.setQueryData(['sections', tabUUID], optimisticResult);
            }
            
            return { previousSectionsData };
        },
        onError: (error, newSection, context) => {
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