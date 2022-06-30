import { useMutation, useQueryClient } from 'react-query';
import produce from 'immer';
import { GuideType } from '@shared/types/guide_types';
import { updateGuide } from '@api/guide/GuideAPI';

export function useUpdateGuide(subjectId: number) {
    const queryClient = useQueryClient();
    return useMutation(updateGuide, {
        onMutate: async (updatedGuide:Record<string, any>) => {
            await queryClient.cancelQueries(['guide', subjectId]);
            const previousGuideData = queryClient.getQueryData<GuideType>(['guide', subjectId]);
            
            if (previousGuideData) {
                const optimisticResult = produce<GuideType, any>(previousGuideData, draftData => {
                    Object.keys(updatedGuide.data).map((key: string) => {
                        draftData[key] = updatedGuide.data[key];
                    });
                });

                queryClient.setQueryData(['guide', subjectId], optimisticResult);
            }
            return { previousGuideData };
        },
        onError: (error, guideData, context) => {
            // Perform rollback of guide mutation
            if (error) {
                console.error(error);
            }

            if (context) {
                queryClient.setQueryData(['guide', subjectId], context.previousGuideData);
            }
        },
        onSettled: () => {
            // Refetch the guide data
            queryClient.invalidateQueries(['guide', subjectId]);
        },
    });
}