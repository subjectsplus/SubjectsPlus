import React from 'react';
import Pluslet from './Pluslet';
import { useFetchPluslets } from '#api/guide/PlusletAPI';
import { Draggable, Droppable } from 'react-beautiful-dnd';
import { useDeleteSection } from '#api/guide/SectionAPI';
import Col from 'react-bootstrap/Col';
import Row from 'react-bootstrap/Row';

function Section({ tabId, sectionId, layout, sectionIndex }) {
    const {isLoading, isError, data, error} = useFetchPluslets(sectionId);

    const deleteSectionMutation = useDeleteSection(tabId);

    const getSectionDraggableStyle = (isDragging, draggableStyle) => ({
        position: 'relative',
        marginBottom: '2.5rem',
        border: '1px dotted #b5b5b5',
        padding: '0.5rem .75rem',
        background: isDragging ? 'rgba(63,194,198, 15%)' : 'transparent',
        ...draggableStyle
      });

    const getSectionStyle = (isDraggingOver) => ({
        backgroundColor: isDraggingOver ? "rgba(0,0,0, 5%)" : "transparent"
    });

    const deleteSection = () => {
        const confirmed = confirm('Are you sure you want to delete this section?');
        if (confirmed) {
            deleteSectionMutation.mutate(sectionId);
        }
    }

    const generateColumns = () => {
        const splitLayout = layout.split('-');
        let column = 0;
        let pluslets = Array.isArray(data) ? [...data] : null;

        const columns = splitLayout.map(size => {
            if (Number(size) !== 0) {
                let columnPluslets;

                if (pluslets && pluslets.length > 0) {
                    columnPluslets = pluslets.filter(pluslet => pluslet.pcolumn === column)
                    .filter(pluslet => pluslet !== undefined)
                    .map((pluslet, row) => (
                        <Pluslet key={pluslet.plusletId} 
                            plusletId={pluslet.plusletId} plusletRow={row} />)
                    );
                }

                const columnId = `section|${sectionId.toString()}|column|${column++}`;
                
                return (     
                    <Col key={columnId} lg={Number(size)} className="mb-3 mb-lg-0">
                        <Droppable type="pluslet" style={{ transform: 'none' }}
                            droppableId={columnId} direction="vertical">
                            {(provided, snapshot) => (
                                <div className="sp-guide-column" {...provided.droppableProps} ref={provided.innerRef}>
                                    <span className="visually-hidden">{columnId}</span>
                                    <div className="text-center mb-2">
                                        <button className="btn btn-link p-1" title="Add Pluslet">
                                            <i className="fas fa-plus-circle"></i>
                                        </button>
                                    </div>
                                    {columnPluslets}
                                    {provided.placeholder}
                                </div>
                            )}
                        </Droppable>
                    </Col>
                );
            }
        });

        return columns;
    };

    const sectionContent = () => {
        if (isLoading) {
            return (<p>Loading Pluslets...</p>);
        } else if (isError) {
            console.error(error);
            return (<p>Error: Failed to load sections through API Endpoint!</p>);
        } else {
            return (
                <Draggable type="section" draggableId={'section-' + sectionId} index={sectionIndex}>
                    {(provided, snapshot) => (
                        <div ref={provided.innerRef} {...provided.draggableProps}
                            style={getSectionDraggableStyle(snapshot.isDragging, provided.draggableProps.style)}>
                            <div className="drag-handle sp-section-drag-handle" {...provided.dragHandleProps} title="Move section">
                                <i className="fas fa-arrows-alt"></i>
                            </div>
                            <button className="delete-section btn btn-icon-default sp-section-delete-btn" onClick={deleteSection} title="Delete section">
                                <i className="fas fa-trash"></i>
                            </button>

                            <div className="guide-section" data-layout={layout}
                                style={getSectionStyle(snapshot.isDragging)}>
                                <span className="visually-hidden">Section {sectionId}</span>
                                <Row>
                                    {generateColumns()}
                                </Row>
                            </div>
                        </div>
                    )}
                </Draggable>
            );
        }
    };

    return sectionContent();
}

export default Section;