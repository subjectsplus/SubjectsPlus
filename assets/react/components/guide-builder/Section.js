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

    const padding = 16;

    const getSectionDraggableStyle = (isDragging, draggableStyle) => ({
        padding: `${padding * 2}px`,
        position: 'relative',
        marginBottom: `${padding}px`,
        background: isDragging ? "lightgreen" : "grey",
        width: '900px',
        ...draggableStyle
      });

    const getSectionStyle = (isDraggingOver) => ({
        background: isDraggingOver ? "lightblue" : "lightgrey",
        padding: padding,
    });

    const deleteSection = () => {
        const confirmed = confirm('Are you sure you want to delete this section?');
        if (confirmed) {
            deleteSectionMutation.mutate(sectionId);
        }
    }

    const sectionAnchorStyle = {
        position: 'absolute',
        display: 'block',
        border: '1px solid #000',
        width: '50px',
        height: '20px',
        lineHeight: '20px',
        right: 0,
        top: 0,
        marginTop: '-10px',
        zindex: 1
    }

    const columnStyle = {
        width: '100%',
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
                    .map(pluslet => (
                        <Pluslet key={pluslet.plusletId} 
                            plusletId={pluslet.plusletId} plusletRow={pluslet.prow} />)
                    );
                }

                const columnId = `section-${sectionId.toString()}-column-${column++}`;

                return (     
                    <Col key={columnId} lg={Number(size)}>
                        <h3>{columnId}</h3>
                        <Droppable type="pluslet" style={{ transform: 'none' }} 
                            droppableId={columnId} direction="vertical">
                            {(provided, snapshot) => (
                                <div style={columnStyle} {...provided.droppableProps} ref={provided.innerRef}>
                                    {columnPluslets?.length > 0 ? columnPluslets : <h3>Add a pluslet</h3>}
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
                <Draggable type="section" draggableId={'section-' + sectionIndex} index={sectionIndex}>
                    {(provided, snapshot) => (
                        <div ref={provided.innerRef} {...provided.draggableProps}
                            style={getSectionDraggableStyle(snapshot.isDragging, provided.draggableProps.style)}>
                            <span style={sectionAnchorStyle}>
                                <span className="drag-handle" {...provided.dragHandleProps}>
                                    <i className="fas fa-arrows-alt"></i>
                                </span>
                                <button className="delete-section" onClick={deleteSection}>
                                    <i className="fas fa-trash"></i>
                                </button>
                            </span>
                            <div className="guide-section" data-layout={layout}
                                style={getSectionStyle(snapshot.isDragging)}>
                                <h3>Section {sectionId}</h3>
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