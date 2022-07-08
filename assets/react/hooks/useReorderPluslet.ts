import { useMutation, useQueryClient } from 'react-query';
import produce from 'immer';
import { reorderPluslet } from '@api/guide/PlusletAPI';
import { PlusletType } from '@shared/types/guide_types';

export function useReorderPluslet() {
    const queryClient = useQueryClient();
    return useMutation(reorderPluslet, {
        onMutate: async plusletData => {
            const sourceSection = plusletData.sourceSection;
            const sourceColumn = plusletData.sourceColumn;
            const sourceIndex = plusletData.sourceIndex;
            const destinationSection = plusletData.destinationSection;
            const destinationColumn = plusletData.destinationColumn;
            const destinationIndex = plusletData.destinationIndex;

            await queryClient.cancelQueries(['pluslets', sourceSection]);
            await queryClient.cancelQueries(['pluslets', destinationSection]);

            const sourcePlusletsData = queryClient.getQueryData<Record<string, any>>(['pluslets', sourceSection]);
            const destinationPlusletsData = queryClient.getQueryData<Record<string, any>>(['pluslets', destinationSection]);

            // Produce optimistic result
            if (sourcePlusletsData && destinationPlusletsData) {
                if (sourceSection === destinationSection && sourceColumn === destinationColumn) {
                    const optimisticDestinationPluslets = produce<Record<string, any>>(destinationPlusletsData, draftData => {
                        const columnPluslets = (draftData['hydra:member'] as PlusletType[]).filter(
                            pluslet => pluslet.pcolumn === destinationColumn).filter(
                            pluslet => pluslet !== undefined
                        );
    
                        const updatedPluslets: Record<string, any>= {};
    
                        // Move pluslet within the same column
                        const [reorderedPluslet] = columnPluslets.splice(sourceIndex, 1);
    
                        if (!reorderedPluslet) throw new Error('Failed to find source pluslet to reorder for optimistic result.');
    
                        columnPluslets.splice(destinationIndex, 0, reorderedPluslet);
    
                        // Index the updated prow
                        columnPluslets.forEach((pluslet, index) => {
                            if (pluslet.prow !== index) {
                                updatedPluslets[pluslet.id] = {
                                    prow: index
                                };
                            }
                        });
    
                        // Set the updated prow
                        (draftData['hydra:member'] as PlusletType[]).forEach((pluslet, index) => {
                            if (updatedPluslets[pluslet.id]) {
                                draftData['hydra:member'][index] = produce<PlusletType>(pluslet, draftPluslet => {
                                    draftPluslet.prow = updatedPluslets[pluslet.id].prow;
                                });
                            }
                        });
    
                        // Resort the pluslets
                        (draftData['hydra:member'] as PlusletType[]).sort((plusletA, plusletB) => {
                            if (plusletA.pcolumn === plusletB.pcolumn) {
                                return plusletA.prow - plusletB.prow;
                            }
                            return plusletA.pcolumn - plusletB.pcolumn;
                        });
                    });
        
                    queryClient.setQueryData(['pluslets', destinationSection], optimisticDestinationPluslets);
        
                    return { destinationPlusletsData };
                } else {
                    const sourceColumnPluslets = (sourcePlusletsData['hydra:member'] as PlusletType[]).filter(
                        pluslet => pluslet.pcolumn === sourceColumn).filter(
                        pluslet => pluslet !== undefined
                    );
    
                    const updatedPluslets: Record<string, any>= {};
    
                    // Remove pluslet from source column
                    const [reorderedPluslet] = sourceColumnPluslets.splice(sourceIndex, 1);
                    
                    if (!reorderedPluslet) throw new Error('Failed to find source pluslet to reorder for optimistic result.');
    
                    // Set the updated prow for source column pluslets
                    sourceColumnPluslets.forEach((pluslet, index) => {
                        if (pluslet.prow !== index) {
                            updatedPluslets[pluslet.id] = {
                                prow: index,
                                pcolumn: sourceColumn
                            };
                        }
                    });
    
                    // Move pluslet to a different section/column
                    const destinationColumnPluslets = (destinationPlusletsData['hydra:member'] as PlusletType[]).filter(
                        pluslet => pluslet.pcolumn === destinationColumn).filter(
                            pluslet => pluslet !== undefined
                    );
    
                    // Add to destination column and reorder destination column pluslets 
                    destinationColumnPluslets.splice(destinationIndex, 0, reorderedPluslet);
    
                    // Set the updated prow/pcolumn for destination column pluslets
                    destinationColumnPluslets.forEach((pluslet, index) => {
                        if (pluslet.prow !== index || pluslet.pcolumn !== destinationColumn
                            || pluslet.id === reorderedPluslet.id) {
                            updatedPluslets[pluslet.id] = {
                                prow: index,
                                pcolumn: destinationColumn
                            };
                        }
                    });
    
                    const optimisticSourcePluslets = produce<Record<string, any>>(sourcePlusletsData, draftData => {
                        // Pluslet must be removed from sourcePluslets if not the same section
                        if (sourceSection !== destinationSection) {
                            const plusletIndex = (draftData['hydra:member'] as PlusletType[]).findIndex(pluslet => pluslet.id === reorderedPluslet.id);
                            (draftData['hydra:member'] as PlusletType[]).splice(plusletIndex, 1);
                        }
    
                        // Set the updated prow/pcolumn
                        (draftData['hydra:member'] as PlusletType[]).forEach((pluslet, index) => {
                            if (updatedPluslets[pluslet.id]) {
                                draftData['hydra:member'][index] = produce(pluslet, draftPluslet => {
                                    draftPluslet.prow = updatedPluslets[pluslet.id].prow;
                                    draftPluslet.pcolumn = updatedPluslets[pluslet.id].pcolumn;
                                });
                            }
                        });
    
                        // Resort the pluslets
                        (draftData['hydra:member'] as PlusletType[]).sort((plusletA, plusletB) => {
                            if (plusletA.pcolumn === plusletB.pcolumn) {
                                return plusletA.prow - plusletB.prow;
                            }
                            return plusletA.pcolumn - plusletB.pcolumn;
                        });
                    });
    
                    const optimisticDestinationPluslets = produce<Record<string, any>>(destinationPlusletsData, draftData => {
                        // Pluslet must be added to destinationPluslets if not the same section
                        if (sourceSection !== destinationSection) {
                            (draftData['hydra:member'] as PlusletType[]).push(reorderedPluslet);
                        }
    
                        // Set the updated prow/pcolumn
                        (draftData['hydra:member'] as PlusletType[]).forEach((pluslet, index) => {
                            if (updatedPluslets[pluslet.id]) {
                                draftData['hydra:member'][index] = produce(pluslet, draftPluslet => {
                                    draftPluslet.prow = updatedPluslets[pluslet.id].prow;
                                    draftPluslet.pcolumn = updatedPluslets[pluslet.id].pcolumn;
                                });
                            }
                        });
    
                        // Resort the pluslets
                        (draftData['hydra:member'] as PlusletType[]).sort((plusletA, plusletB) => {
                            if (plusletA.pcolumn === plusletB.pcolumn) {
                                return plusletA.prow - plusletB.prow;
                            }
                            return plusletA.pcolumn - plusletB.pcolumn;
                        });
                    });
    
                    // Set optimistic result to query data
                    queryClient.setQueryData(['pluslets', sourceSection], optimisticSourcePluslets);
                    queryClient.setQueryData(['pluslets', destinationSection], optimisticDestinationPluslets);
    
                    return { sourcePlusletsData, destinationPlusletsData };
                }
            }
        },
        onError: (error, plusletData, context) => {
            // Perform rollback of pluslet order mutation
            if (error) {
                console.error(error);
            }
            
            if (context) {
                if (context.sourcePlusletsData) {
                    queryClient.setQueryData(['pluslets', plusletData.sourceSection], context.sourcePlusletsData);
                }

                queryClient.setQueryData(['pluslets', plusletData.destinationSection], context.destinationPlusletsData);
            }
        },
        onSettled: (data, error, plusletData) => {
            // Refetch the pluslet order data
            if (plusletData.sourceSection !== plusletData.destinationSection) {
                queryClient.invalidateQueries(['pluslets', plusletData.sourceSection]);
            }
            
            queryClient.invalidateQueries(['pluslets', plusletData.destinationSection]);
        }
    });
}