import { useState } from 'react';
import SectionColumn from './SectionColumn';
import SectionDropdown from './SectionDropdown';
import DeleteConfirmModal from '#components/shared/DeleteConfirmModal';
import { useFetchPluslets, useCreatePluslet } from '#api/guide/PlusletAPI';
import { useConvertSectionLayout, useDeleteSection } from '#api/guide/SectionAPI';
import { Draggable } from 'react-beautiful-dnd';
import Row from 'react-bootstrap/Row';
import { v4 as uuidv4 } from 'uuid';

function Section({ tabId, sectionId, layout, sectionIndex, currentDraggingId, currentEditablePluslet, currentEditablePlusletCallBack }) {
    const {isLoading, isError, data, error} = useFetchPluslets(sectionId);

    const deleteSectionMutation = useDeleteSection(tabId);
    const createPlusletMutation = useCreatePluslet(sectionId);
    const convertSectionLayoutMutation = useConvertSectionLayout(sectionId);

    const [deleteSectionClicked, setDeleteSectionClicked] = useState(false);
    const [isConvertingLayout, setIsConvertingLayout] = useState(false);

    const isCurrentlyDragging = (('section-' + sectionId) === currentDraggingId);
    const isDraggingOver = (currentDraggingId !== null && currentDraggingId.substring(0, 7) === 'section' 
        && ('section-' + sectionId) !== currentDraggingId);

    const getSectionWindowClassName = (isDragging) => {
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
            sectionId: sectionId,
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

    const handleConvertSectionLayout = (newLayout) => {
        if (layout !== newLayout) {
            convertSectionLayout(newLayout);
        }
    }

    const convertSectionLayout = (newLayout) => {
        console.log('Starting conversion from', layout, 'to', newLayout, 'layout');
        setIsConvertingLayout(true);
        convertSectionLayoutMutation.mutate({
            sectionId: sectionId,
            newLayout: newLayout,
            sectionIndex: sectionIndex,
            tabId: tabId
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

    const addPluslet = (column, row) => {
        if (Array.isArray(data)) {
            const initialPlusletData = {
                id: uuidv4(),
                title: "",
                type: "Basic",
                body: "",
                pcolumn: column,
                prow: row,
                section: '/api/sections/' + sectionId
            }

            console.log('initialPlusletData: ', initialPlusletData);
            createPlusletMutation.mutate(initialPlusletData);
            currentEditablePlusletCallBack(initialPlusletData.id);
        }
    }

    const generateColumns = () => {
        const splitLayout = layout.split('-');
        let column = 0;
        let pluslets = Array.isArray(data) ? [...data] : null;

        const columns = splitLayout.map(size => {
            if (Number(size) !== 0) {
                let columnPluslets;

                // set current column and increment by 1
                const currentColumn = column++;

                if (pluslets && pluslets.length > 0) {
                    columnPluslets = pluslets.filter(pluslet => pluslet.pcolumn === currentColumn)
                    .filter(pluslet => pluslet !== undefined);
                }

                const columnRows = Array.isArray(columnPluslets) ? columnPluslets.length : 0;
                const columnId = `section|${sectionId.toString()}|column|${currentColumn}`;

                return (
                    <SectionColumn key={columnId} columnId={columnId} column={currentColumn} sectionId={sectionId} 
                        pluslets={columnPluslets} columnSize={Number(size)} currentDraggingId={currentDraggingId} 
                        addPlusletOnClick={() => addPluslet(currentColumn, columnRows)} currentEditablePluslet={currentEditablePluslet} 
                        currentEditablePlusletCallBack={currentEditablePlusletCallBack} /> 
                );
            }
        });

        return columns;
    };

    const sectionContent = () => {
        if (isLoading) {
            return (<p>Loading Boxes...</p>);
        } else if (isError) {
            console.error(error);
            return (<p>Error: Failed to load sections through API Endpoint!</p>);
        } else {
            return (
                <>
                    <Draggable type="section" draggableId={'section-' + sectionId} index={sectionIndex}>
                        {(provided, snapshot) => (
                            <div className={getSectionWindowClassName(snapshot.isDragging || isCurrentlyDragging)}
                                ref={provided.innerRef} {...provided.draggableProps}>
                                {/* Section Drag Handle */}
                                <div className="drag-handle sp-section-drag-handle" {...provided.dragHandleProps} title="Move section">
                                    <i className="fas fa-arrows-alt"></i>
                                </div>

                                {/* Section Dropdown */}
                                <SectionDropdown isConvertingLayout={isConvertingLayout} sectionDelete={handleSectionDelete} 
                                    convertLayout={handleConvertSectionLayout} />
                                
                                {/* Section Content */}
                                <div className="sp-section-content" data-layout={layout}>
                                    <span className="visually-hidden">Section {sectionId}</span>
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
    };

    return sectionContent();
}

export default Section;