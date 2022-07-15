import { useState } from 'react';
import { Draggable } from 'react-beautiful-dnd';
import Row from 'react-bootstrap/Row';
import { v4 as uuidv4 } from 'uuid';
import { SectionColumn } from './SectionColumn';
import { SectionDropdown } from './SectionDropdown';
import { DeleteConfirmModal } from '@components/shared/DeleteConfirmModal';
import { useFetchPluslets } from '@hooks/useFetchPluslets';
import { useCreatePluslet } from '@hooks/useCreatePluslet';
import { useConvertSectionLayout } from '@hooks/useConvertSectionLayout';
import { useDeleteSection } from '@hooks/useDeleteSection';
import { PlusletType } from '@shared/types/guide_types';
import { useSectionContainer, SectionContainerType } from '@context/SectionContainerContext';

type SectionProps = {
    sectionUUID: string,
    layout: string,
    sectionIndex: number,
    tabUUID: string
};

export const Section = ({ sectionUUID, layout, sectionIndex, tabUUID }: SectionProps) => {
    const { currentDraggingId, setCurrentEditablePluslet } = useSectionContainer() as SectionContainerType;

    const {isLoading, isError, data, error} = useFetchPluslets(sectionUUID);

    const deleteSectionMutation = useDeleteSection(tabUUID);
    const createPlusletMutation = useCreatePluslet(sectionUUID);
    const convertSectionLayoutMutation = useConvertSectionLayout(sectionUUID);

    const [deleteSectionClicked, setDeleteSectionClicked] = useState(false);
    const [isConvertingLayout, setIsConvertingLayout] = useState(false);

    const isCurrentlyDragging = (('section-' + sectionUUID) === currentDraggingId);
    const isDraggingOver = (currentDraggingId !== null && currentDraggingId.substring(0, 7) === 'section' 
        && ('section-' + sectionUUID) !== currentDraggingId);

    const getSectionWindowClassName = (isDragging: boolean) => {
        let className = 'sp-section';

        if (isDragging) {
            className += ' sp-section-dragging';
        }

        if (isDraggingOver) {
            className += ' sp-section-window-dragging-over';
        }

        return className;
    }

    const deleteSection = () => {
        deleteSectionMutation.mutate({
            sectionUUID: sectionUUID
        });
        setDeleteSectionClicked(false);
    }

    const handleSectionDelete = () => {
        if (deleteSectionClicked) {
            deleteSection();
        } else {
            setDeleteSectionClicked(true);
        }
    }

    const handleConvertSectionLayout = (newLayout: string) => {
        if (layout !== newLayout) {
            convertSectionLayout(newLayout);
        }
    }

    const convertSectionLayout = (newLayout: string) => {
        console.log('Starting conversion from', layout, 'to', newLayout, 'layout');
        setIsConvertingLayout(true);
        convertSectionLayoutMutation.mutate({
            sectionUUID: sectionUUID,
            newLayout: newLayout,
            sectionIndex: sectionIndex,
            tabUUID: tabUUID
        }, {
            onError: (error) => {
                console.log('Conversion from', layout, 'to', newLayout, 'layout failed due to error');
                console.error(error);
            },
            onSettled: () => {
                console.log('Conversion from', layout, 'to', newLayout, 'layout is settled');
                setIsConvertingLayout(false);
            }
        });
    }

    const addPluslet = (column: number, row: number) => {
        if (Array.isArray(data)) {
            const initialPlusletData = {
                id: uuidv4(),
                title: '',
                type: 'Basic',
                body: '',
                pcolumn: column,
                prow: row,
                section: '/api/sections/' + sectionUUID
            }

            createPlusletMutation.mutate(initialPlusletData);
            setCurrentEditablePluslet(initialPlusletData.id);
        }
    }

    const generateColumns = () => {
        const splitLayout = layout.split('-');
        let column = 0;
        let pluslets: PlusletType[]|null = Array.isArray(data) ? [...data] : null;

        const columns = splitLayout.map(size => {
            if (Number(size) !== 0) {
                let columnPluslets: PlusletType[]|null;

                // set current column and increment by 1
                const currentColumn = column++;

                if (pluslets && pluslets.length > 0) {
                    columnPluslets = pluslets.filter(pluslet => pluslet.pcolumn === currentColumn)
                    .filter(pluslet => pluslet !== undefined);
                } else {
                    columnPluslets = null;
                }

                const columnRows = Array.isArray(columnPluslets) ? columnPluslets.length : 0;
                const columnId = `section|${sectionUUID.toString()}|column|${currentColumn}`;

                return (
                    <SectionColumn key={columnId} columnId={columnId} sectionUUID={sectionUUID} 
                        pluslets={columnPluslets} columnSize={Number(size)} 
                        addPlusletOnClick={() => addPluslet(currentColumn, columnRows)} /> 
                );
            }
        });

        return columns;
    };

    if (isLoading) {
        return (<p>Loading Boxes...</p>);
    } else if (isError) {
        console.error(error);
        return (<p>Error: Failed to load sections through API Endpoint!</p>);
    } else {
        return (
            <>
                <Draggable draggableId={'section-' + sectionUUID} index={sectionIndex}>
                    {(provided, snapshot) => (
                        <div className={getSectionWindowClassName(snapshot.isDragging || isCurrentlyDragging)}
                            ref={provided.innerRef} {...provided.draggableProps}>
                            {/* Section Drag Handle */}
                            <div className="drag-handle sp-section-drag-handle" {...provided.dragHandleProps} title="Move section">
                                <i className="fas fa-arrows-alt"></i>
                            </div>

                            {/* Section Dropdown */}
                            <SectionDropdown isConvertingLayout={isConvertingLayout} deleteSectionOnClick={handleSectionDelete} 
                                convertLayoutCallback={handleConvertSectionLayout} />
                            
                            {/* Section Content */}
                            <div className="sp-section-content" data-layout={layout}>
                                <span className="visually-hidden">Section {sectionUUID}</span>
                                <Row className={(snapshot.isDragging || isCurrentlyDragging) ? 'visually-hidden' : ''}>
                                    {generateColumns()}
                                </Row>
                            </div>
                        </div>
                    )}
                </Draggable>

                {/* Delete Confirmation Modal */}
                <DeleteConfirmModal show={deleteSectionClicked} resourceName="Section" onHide={() => setDeleteSectionClicked(false)}
                    confirmOnClick={handleSectionDelete} />
            </>
        );
    }
}