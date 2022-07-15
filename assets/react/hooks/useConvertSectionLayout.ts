import { useMutation, useQueryClient } from 'react-query';
import produce from 'immer';
import { convertSectionLayout } from '@api/guide/SectionAPI';
import { GuideSectionType, PlusletType } from '@shared/types/guide_types';

export const useConvertSectionLayout = (sectionUUID: string) => {
    const queryClient = useQueryClient();
    return useMutation(convertSectionLayout, {
        onMutate: async updatedSection => {
            await queryClient.cancelQueries(['pluslets', sectionUUID]);
            await queryClient.cancelQueries(['sections', updatedSection.tabUUID]);
            const previousPlusletsData = queryClient.getQueryData<PlusletType[]>(['pluslets', sectionUUID]);
            const previousSectionsData = queryClient.getQueryData<GuideSectionType[]>(['sections', updatedSection.tabUUID]);

            if (previousPlusletsData && previousSectionsData) {
                const plusletsOptimisticResult = produce<PlusletType[]>(previousPlusletsData, draftData => {
                    if (updatedSection.sectionIndex) {
                        const previousSection = previousSectionsData[updatedSection.sectionIndex];
                        const oldLayout = previousSection.layout;
                        const oldLayoutSizes = oldLayout.split('-');
                        const oldLayoutTotalColumns = (oldLayoutSizes.filter(layout => Number(layout) !== 0)).length;
                        const newLayoutSizes = updatedSection.newLayout.split('-');
                        const newLayoutTotalColumns = (newLayoutSizes.filter(layout => Number(layout) !== 0)).length;
                        const newLayoutLastColumn = newLayoutTotalColumns - 1;
                        const updates: Record<string, Record<'pcolumn'|'prow', number>> = {};
        
                        if (oldLayoutTotalColumns > newLayoutTotalColumns) {
                            // If the old layout has more columns than the new layout, any excess pluslets from the old layout
                            // will join the last column of the new layout.
                            
                            // Filter pluslets with pcolumn greater than or equal to last column of the new layout then sort by pcolumn and prow
                            const columnPluslets = draftData.filter((pluslet: PlusletType) => pluslet.pcolumn >= newLayoutLastColumn)
                            .sort((plusletA: PlusletType, plusletB: PlusletType) => {
                                if (plusletA.pcolumn === plusletB.pcolumn) {
                                    return plusletA.prow - plusletB.prow;
                                }
                                return plusletA.pcolumn - plusletB.pcolumn;
                            });
        
                            columnPluslets.forEach((pluslet: PlusletType, index: number) => {
                                if (pluslet.pcolumn !== newLayoutLastColumn || pluslet.prow !== index) {
                                    updates[pluslet.id] = {
                                        pcolumn: newLayoutLastColumn,
                                        prow: index
                                    };
                                }
                            });
        
                            draftData.forEach((pluslet: PlusletType) => {
                                if (updates[pluslet.id]) {
                                    pluslet.pcolumn = updates[pluslet.id].pcolumn;
                                    pluslet.prow = updates[pluslet.id].prow;
                                }
                            });
                        }
                    }
                });

                const sectionsOptimisticResult = produce<GuideSectionType[]>(previousSectionsData, draftData => {
                    // Update Section layout property to new layout
                    if (typeof updatedSection.sectionIndex !== 'undefined') {
                        draftData[updatedSection.sectionIndex]['layout'] = updatedSection.newLayout;
                    }
                });

                queryClient.setQueryData(['sections', updatedSection.tabUUID], sectionsOptimisticResult);
                queryClient.setQueryData(['pluslets', sectionUUID], plusletsOptimisticResult);
            }

            return { previousPlusletsData, previousSectionsData };
        },
        onError: (error, sectionData, context) => {
            // Perform rollback of section and pluslets mutation
            if (error) {
                console.error(error);
            }

            if (context) {
                queryClient.setQueryData(['pluslets', sectionUUID], context.previousPlusletsData);
                queryClient.setQueryData(['sections', sectionData.tabUUID], context.previousSectionsData);
            }
        },
        onSettled: (data, error, sectionData, context) => {
            // Refetch the section and pluslets data
            queryClient.invalidateQueries(['pluslets', sectionUUID]);
            queryClient.invalidateQueries(['sections', sectionData.tabUUID]);
        },
    });
}